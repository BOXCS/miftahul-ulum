/**
 * ================================================================
 * Miftahul Ulum — Fingerprint ESP32 (auto-discover server)
 * ================================================================
 *
 * Tidak ada hardcode IP server. ESP32 scan subnet WiFi otomatis,
 * cari host yang return signature "miftahul_ulum" di /api/iot/ping.
 * Hasil di-cache di NVS (Preferences).
 */

#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <Adafruit_Fingerprint.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <Preferences.h>
#include <time.h>

// ================= KONFIGURASI WIFI =================
#define WIFI_SSID    "Basecamp 1"
#define WIFI_PASS    "gulaaren"

#define SERVER_PORT  8000
#define SERVER_SIG   "miftahul_ulum"

#define BUZZER_PIN       4
#define COOLDOWN_MS      3000
#define POLL_INTERVAL_MS 3000

// ================= NTP & JADWAL SHOLAT =================
// WIB = UTC+7 → 7*3600 detik. Untuk WITA: 8*3600, WIT: 9*3600
#define GMT_OFFSET_SEC      (7 * 3600)
#define DST_OFFSET_SEC      0
#define NTP_SERVER_1        "pool.ntp.org"
#define NTP_SERVER_2        "time.google.com"

// Refresh jadwal sholat setiap 6 jam (atau saat hari berubah)
#define PRAYER_REFRESH_MS   (6UL * 60 * 60 * 1000)

// ================= HARDWARE =================
LiquidCrystal_I2C lcd(0x27, 16, 2);
HardwareSerial mySerial(2);
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);
Preferences prefs;

String serverBase = "";
unsigned long lastScanTime  = 0;
unsigned long lastPollTime  = 0;
unsigned long lastPrayerFetch = 0;
bool ntpSynced = false;

// Counter berapa kali request berturut-turut gagal sebelum invalidate cache.
// Mencegah re-scan subnet hanya karena 1 packet loss / server slow respond.
int  consecutiveFailures = 0;
const int MAX_CONSECUTIVE_FAILURES = 5;

// Jadwal sholat hari ini — di-fetch dari server
struct PrayerWindow {
  char name[12];
  int startMinOfDay;  // misal 04:21 → 4*60+21 = 261
  int endMinOfDay;    // 05:21 → 321
};
PrayerWindow todayPrayers[5];
int prayerCount = 0;
String prayerDateCached = "";  // YYYY-MM-DD

// State LCD idle screen — cache untuk hindari flicker
String lastIdleTopLine = "";
String lastIdleBottomLine = "";

// ================= BUZZER =================
void beepSuccess() {
  digitalWrite(BUZZER_PIN, HIGH); delay(100);
  digitalWrite(BUZZER_PIN, LOW);  delay(100);
  digitalWrite(BUZZER_PIN, HIGH); delay(100);
  digitalWrite(BUZZER_PIN, LOW);
}
void beepError() {
  digitalWrite(BUZZER_PIN, HIGH); delay(500);
  digitalWrite(BUZZER_PIN, LOW);
}

// ================= LCD HELPER =================
void lcdPrint(const String& l1, const String& l2 = "") {
  lcd.clear();
  lcd.setCursor(0, 0); lcd.print(l1.substring(0, 16));
  lcd.setCursor(0, 1); lcd.print(l2.substring(0, 16));
}

// Forward declaration (dipanggil sebelum currentPrayerIndex didefinisikan)
int currentPrayerIndex();

/**
 * Render satu baris LCD dengan padding spasi sampai 16 karakter.
 * Pakai partial update (cursor + print) supaya tidak flicker.
 */
void renderLcdLine(uint8_t row, const String& text, String& cache) {
  String padded = text;
  if (padded.length() > 16) padded = padded.substring(0, 16);
  while (padded.length() < 16) padded += " ";
  if (padded != cache) {
    lcd.setCursor(0, row);
    lcd.print(padded);
    cache = padded;
  }
}

/**
 * Tampilan standby (idle): nama sholat aktif + jam HH:MM:SS realtime.
 * Dipanggil tiap iterasi loop saat tidak ada jari terdeteksi.
 * Partial update agar tidak flicker (hanya redraw kalau berubah).
 */
void showIdleScreen() {
  // Baris 1: nama sholat aktif atau status
  String topLine;
  if (!ntpSynced) {
    topLine = "Sync waktu...";
  } else {
    int idx = currentPrayerIndex();
    if (idx >= 0) {
      topLine = String(todayPrayers[idx].name);
    } else {
      topLine = "Di luar sholat";
    }
  }

  // Baris 2: jam HH:MM:SS (center, 8 char dengan padding 4 spasi)
  String bottomLine = "Tunggu NTP";
  if (ntpSynced) {
    struct tm t;
    if (getLocalTime(&t)) {
      char buf[10];
      snprintf(buf, sizeof(buf), "%02d:%02d:%02d", t.tm_hour, t.tm_min, t.tm_sec);
      bottomLine = "    " + String(buf) + "    ";  // pad agar centered di 16 kolom
    }
  }

  renderLcdLine(0, topLine, lastIdleTopLine);
  renderLcdLine(1, bottomLine, lastIdleBottomLine);
}

/**
 * Tandai bahwa LCD perlu redraw ke idle screen.
 * Dipanggil setelah menampilkan hasil scan/error agar idle screen
 * di-rebuild dari nol di iterasi loop berikutnya.
 */
void backToIdle() {
  lastIdleTopLine = "";     // invalidate cache → force redraw
  lastIdleBottomLine = "";
}

/**
 * Helper: setup HTTPClient dengan header standar JSON API.
 * Wajib pakai Accept: application/json agar Laravel tidak return 302 redirect
 * saat validation gagal (behavior web fallback).
 */
void setupJsonHeaders(HTTPClient& http) {
  http.addHeader("Content-Type", "application/json");
  http.addHeader("Accept", "application/json");
  http.addHeader("X-Requested-With", "XMLHttpRequest");
}

// ================= WIFI =================
void connectWifi() {
  lcdPrint("Konek WiFi...", WIFI_SSID);
  Serial.printf("\nConnect ke '%s'\n", WIFI_SSID);
  WiFi.disconnect(true);
  delay(300);
  WiFi.mode(WIFI_STA);
  WiFi.setSleep(false);
  WiFi.begin(WIFI_SSID, WIFI_PASS);

  int tries = 0;
  while (WiFi.status() != WL_CONNECTED && tries < 40) {
    delay(500); Serial.print("."); tries++;
  }
  Serial.println();

  if (WiFi.status() == WL_CONNECTED) {
    Serial.printf("WiFi OK: %s\n", WiFi.localIP().toString().c_str());
    lcdPrint("WiFi OK", WiFi.localIP().toString());
    beepSuccess();
  } else {
    lcdPrint("WiFi Gagal", "Cek Setting");
    beepError();
  }
  delay(1500);
}

// ================= AUTO-DISCOVER SERVER =================
bool probeHost(const String& ip) {
  HTTPClient http;
  String url = "http://" + ip + ":" + String(SERVER_PORT) + "/api/iot/ping";
  http.begin(url);
  http.addHeader("Accept", "application/json");
  http.setTimeout(400);
  http.setConnectTimeout(300);

  int code = http.GET();
  bool ok = false;
  if (code == 200) {
    String resp = http.getString();
    StaticJsonDocument<128> doc;
    if (!deserializeJson(doc, resp)) {
      String sig = doc["server"] | "";
      ok = (sig == SERVER_SIG);
    }
  }
  http.end();
  return ok;
}

void saveServerIp(const String& ip) {
  prefs.begin("iot", false);
  prefs.putString("server_ip", ip);
  prefs.end();
  Serial.println("✓ Server: " + ip + " (cached)");
  lcdPrint("Server OK", ip);
  beepSuccess();
  delay(1500);
}

bool discoverServer() {
  lcdPrint("Cari server...", "Mode auto");
  Serial.println("\n=== DISCOVERY ===");

  IPAddress me = WiFi.localIP();
  IPAddress gw = WiFi.gatewayIP();
  String prefix = String(me[0]) + "." + me[1] + "." + me[2] + ".";

  // 1. Cached
  prefs.begin("iot", true);
  String cached = prefs.getString("server_ip", "");
  prefs.end();
  if (cached.length() > 0) {
    Serial.printf("Try cached: %s\n", cached.c_str());
    lcdPrint("Coba cache", cached);
    if (probeHost(cached)) {
      serverBase = "http://" + cached + ":" + String(SERVER_PORT);
      Serial.println("✓ Cached HIT: " + serverBase);
      return true;
    }
  }

  // 2. Gateway
  String gwIp = String(gw[0]) + "." + gw[1] + "." + gw[2] + "." + gw[3];
  Serial.printf("Try gateway: %s\n", gwIp.c_str());
  if (probeHost(gwIp)) {
    serverBase = "http://" + gwIp + ":" + String(SERVER_PORT);
    saveServerIp(gwIp);
    return true;
  }

  // 3. Priority IPs
  uint8_t priority[] = {1, 2, 3, 4, 5, 10, 100, 101, 102, 103, 104, 105, 110, 120, 150, 253};
  for (uint8_t i : priority) {
    if (i == me[3]) continue;
    String ip = prefix + String(i);
    lcdPrint("Scan...", ip);
    Serial.print(" ."); Serial.print(i);
    if (probeHost(ip)) {
      Serial.println();
      serverBase = "http://" + ip + ":" + String(SERVER_PORT);
      saveServerIp(ip);
      return true;
    }
  }

  // 4. Full scan
  Serial.println("\nFull scan...");
  for (int i = 1; i <= 254; i++) {
    if (i == me[3]) continue;
    String ip = prefix + String(i);
    if (i % 20 == 0) lcdPrint("Scan...", ip);
    if (probeHost(ip)) {
      Serial.println();
      serverBase = "http://" + ip + ":" + String(SERVER_PORT);
      saveServerIp(ip);
      return true;
    }
  }

  Serial.println("\n✗ Server tidak ditemukan");
  return false;
}

void invalidateCache() {
  prefs.begin("iot", false);
  prefs.remove("server_ip");
  prefs.end();
  serverBase = "";
}

// ================= NTP TIME SYNC =================
/**
 * Sync waktu via NTP. Pakai zona waktu lokal (WIB/WITA/WIT).
 * Wajib dilakukan setelah WiFi connect agar local time akurat.
 */
void syncNtpTime() {
  Serial.println("NTP sync...");
  lcdPrint("Sync waktu...", "NTP");
  configTime(GMT_OFFSET_SEC, DST_OFFSET_SEC, NTP_SERVER_1, NTP_SERVER_2);

  struct tm t;
  if (getLocalTime(&t, 10000)) {
    Serial.printf("✓ Time OK: %04d-%02d-%02d %02d:%02d:%02d\n",
      t.tm_year + 1900, t.tm_mon + 1, t.tm_mday,
      t.tm_hour, t.tm_min, t.tm_sec);
    ntpSynced = true;
    char buf[20];
    snprintf(buf, sizeof(buf), "%02d:%02d %02d-%02d-%02d", t.tm_hour, t.tm_min, t.tm_mday, t.tm_mon + 1, (t.tm_year + 1900) % 100);
    lcdPrint("NTP Sync OK", buf);
  } else {
    Serial.println("✗ NTP gagal");
    lcdPrint("NTP Gagal", "Pakai lokal");
    ntpSynced = false;
  }
  delay(1500);
}

/** Konversi "HH:MM:SS" → menit dalam hari */
int parseTimeToMin(const String& s) {
  int h = s.substring(0, 2).toInt();
  int m = s.substring(3, 5).toInt();
  return h * 60 + m;
}

/**
 * Fetch jadwal sholat dari server (yang sudah cache dari Aladhan API).
 * Parse dan simpan ke array todayPrayers[].
 */
bool fetchPrayerSchedule() {
  if (WiFi.status() != WL_CONNECTED || serverBase.length() == 0) return false;

  Serial.println("Fetch jadwal sholat...");
  HTTPClient http;
  http.begin(serverBase + "/api/iot/prayer-times");
  http.addHeader("Accept", "application/json");
  http.setTimeout(8000);
  int code = http.GET();

  if (code != 200) {
    Serial.printf("Prayer fetch HTTP %d\n", code);
    http.end();
    return false;
  }

  String resp = http.getString();
  http.end();

  StaticJsonDocument<2048> doc;
  if (deserializeJson(doc, resp)) {
    Serial.println("Prayer JSON parse error");
    return false;
  }

  prayerDateCached = String((const char*)(doc["date"] | ""));
  JsonArray windows = doc["windows"];
  prayerCount = 0;
  for (JsonObject w : windows) {
    if (prayerCount >= 5) break;
    const char* name = w["name"] | "";
    String startStr  = String((const char*)(w["start"] | "00:00:00"));
    String endStr    = String((const char*)(w["end"]   | "00:00:00"));

    strncpy(todayPrayers[prayerCount].name, name, sizeof(todayPrayers[prayerCount].name) - 1);
    todayPrayers[prayerCount].name[sizeof(todayPrayers[prayerCount].name) - 1] = '\0';
    todayPrayers[prayerCount].startMinOfDay = parseTimeToMin(startStr);
    todayPrayers[prayerCount].endMinOfDay   = parseTimeToMin(endStr);
    Serial.printf("  %s: %s - %s\n", name, startStr.c_str(), endStr.c_str());
    prayerCount++;
  }

  lastPrayerFetch = millis();
  Serial.printf("✓ %d window jadwal sholat dimuat\n", prayerCount);
  return prayerCount > 0;
}

/**
 * Cek apakah saat ini di dalam window sholat (lokal, pakai NTP).
 * Return index window di todayPrayers[] kalau iya, -1 kalau tidak.
 */
int currentPrayerIndex() {
  if (!ntpSynced || prayerCount == 0) return -1;
  struct tm t;
  if (!getLocalTime(&t)) return -1;

  // Re-fetch kalau hari sudah ganti (jadwal kemarin tidak akurat)
  char todayBuf[12];
  snprintf(todayBuf, sizeof(todayBuf), "%04d-%02d-%02d", t.tm_year + 1900, t.tm_mon + 1, t.tm_mday);
  if (prayerDateCached != String(todayBuf)) {
    Serial.println("Hari berganti, fetch ulang jadwal");
    fetchPrayerSchedule();
    if (prayerCount == 0) return -1;
  }

  int nowMin = t.tm_hour * 60 + t.tm_min;
  for (int i = 0; i < prayerCount; i++) {
    if (nowMin >= todayPrayers[i].startMinOfDay && nowMin <= todayPrayers[i].endMinOfDay) {
      return i;
    }
  }
  return -1;
}

// ================= KIRIM ABSENSI =================
/**
 * Kirim hasil scan ke server. Server selalu jadi sumber kebenaran.
 *   matched=true → ESP32 lapor template ID, server cek DB
 *   matched=false → ESP32 lapor "tidak ada match di sensor"
 * Server response routing:
 *   200 → terdaftar, hadir/terlambat dicatat
 *   404 status=not_recognized → sensor tidak punya template
 *   404 status=orphan_template → sensor punya tapi DB tidak (cacat data)
 */
void kirimAbsensi(uint16_t fingerprintId, bool matched, int confidence) {
  if (WiFi.status() != WL_CONNECTED || serverBase.length() == 0) {
    lcdPrint("Offline!", "Cek WiFi");
    beepError(); delay(2000); backToIdle();
    return;
  }

  lcdPrint("Mengirim...");
  HTTPClient http;
  http.begin(serverBase + "/api/absensi/fingerprint");
  setupJsonHeaders(http);
  http.setTimeout(8000);

  String body = "{\"fingerprint_id\":" + String(fingerprintId) +
                ",\"matched\":" + (matched ? "true" : "false") +
                ",\"confidence\":" + String(confidence) + "}";
  int code = http.POST(body);
  Serial.printf("HTTP %d body=%s\n", code, body.c_str());

  if (code == 200 || code == 201) {
    String resp = http.getString();
    StaticJsonDocument<512> doc;
    if (!deserializeJson(doc, resp)) {
      String nama   = doc["student_name"] | "";
      String waktu  = doc["waktu_shalat"] | "";
      String status = doc["status"]       | "";
      String action = doc["action"]       | "";

      // Format line 2 berdasarkan action:
      //   masuk          → "Dzuhur hadir"
      //   keluar         → "Keluar Dzuhur"
      //   keluar_lintas  → "Lintas → Ashar" (auto-hadir multi-window)
      String line2;
      if (action == "keluar")             line2 = "Keluar " + waktu;
      else if (action == "keluar_lintas") line2 = "Lintas->" + waktu;
      else                                 line2 = waktu + " " + status;

      lcdPrint(nama, line2);
      (status == "terlambat") ? beepError() : beepSuccess();
    } else { lcdPrint("JSON Error"); beepError(); }
  } else if (code == 429) {
    String resp = http.getString();
    StaticJsonDocument<256> doc;
    deserializeJson(doc, resp);
    String msg = doc["message"] | "Cooldown";
    lcdPrint("Tunggu", msg.substring(0, 16));
    beepError();
  }
  else if (code == 404) {
    // Bedakan tidak terdaftar vs orphan template berdasarkan field 'status'
    String resp = http.getString();
    StaticJsonDocument<256> doc;
    deserializeJson(doc, resp);
    String status = doc["status"] | "not_recognized";
    Serial.println("404 status=" + status);
    if (status == "orphan_template") {
      lcdPrint("Data Cacat", "Hubungi Admin");
    } else {
      lcdPrint("Belum Daftar", "Hubungi Admin");
    }
    beepError();
  }
  else if (code == 422) {
    String resp = http.getString();
    Serial.println("422 body: " + resp);
    lcdPrint("Di luar", "Waktu Shalat"); beepError();
  } else if (code == 302) {
    Serial.println("302 redirect — Accept header mungkin missing");
    lcdPrint("Server 302", "Cek header");
    beepError();
  } else if (code < 0) {
    // Jangan langsung invalidate cache — bisa jadi network glitch sesaat.
    // Pakai counter, baru re-discover kalau gagal berturut-turut.
    consecutiveFailures++;
    Serial.printf("Connection fail %d/%d (code=%d)\n",
                  consecutiveFailures, MAX_CONSECUTIVE_FAILURES, code);
    if (consecutiveFailures >= MAX_CONSECUTIVE_FAILURES) {
      Serial.println("Server hilang permanen, invalidate cache");
      invalidateCache();
      consecutiveFailures = 0;
      lcdPrint("Server lost", "Cari ulang...");
    } else {
      lcdPrint("Server lambat", "Retry...");
    }
    beepError();
  } else { lcdPrint("Server Error", "HTTP:" + String(code)); beepError(); }

  // Reset counter saat request sukses (kode 2xx, 4xx, 422, dst)
  if (code >= 200) consecutiveFailures = 0;

  http.end();
  delay(3000);
  backToIdle();
}

// ================= ENROLLMENT =================
int capturePrint(unsigned long timeoutMs) {
  unsigned long start = millis();
  while ((millis() - start) < timeoutMs) {
    if (finger.getImage() == FINGERPRINT_OK) return FINGERPRINT_OK;
    delay(50);
  }
  return -1;
}

void doEnrollment(long commandId, uint16_t targetFp) {
  Serial.printf("\n=== ENROLL id=%d ===\n", targetFp);
  bool success = false;
  String message = "";
  int quality = 0;

  lcdPrint("Daftar 1/2", "Tempel jari"); beepSuccess();
  if (capturePrint(15000) != FINGERPRINT_OK) { message = "Timeout 1"; goto report; }
  if (finger.image2Tz(1) != FINGERPRINT_OK)  { message = "Img2Tz #1 fail"; goto report; }

  lcdPrint("Angkat Jari"); delay(2000);
  while (finger.getImage() != FINGERPRINT_NOFINGER) delay(100);

  lcdPrint("Daftar 2/2", "Tempel ulang");
  if (capturePrint(15000) != FINGERPRINT_OK) { message = "Timeout 2"; goto report; }
  if (finger.image2Tz(2) != FINGERPRINT_OK)  { message = "Img2Tz #2 fail"; goto report; }

  if (finger.createModel() != FINGERPRINT_OK) { message = "Jari tdk match"; goto report; }
  if (finger.storeModel(targetFp) != FINGERPRINT_OK) { message = "Simpan gagal"; goto report; }

  success = true; quality = 95; message = "Berhasil";
  lcdPrint("Berhasil!", "ID: #" + String(targetFp));
  beepSuccess();

report:
  if (!success) {
    lcdPrint("Enroll Gagal", message); beepError();
    // PENTING: bersihkan slot agar tidak jadi orphan template di sensor
    // (kalau storeModel sempat partial atau ada template lama di slot ini)
    finger.deleteModel(targetFp);
    Serial.printf("Slot %d dihapus utk sinkron dgn DB\n", targetFp);
  }

  HTTPClient http;
  http.begin(serverBase + "/api/iot/enroll-complete");
  setupJsonHeaders(http);
  http.setTimeout(8000);

  StaticJsonDocument<256> body;
  body["command_id"]     = commandId;
  body["fingerprint_id"] = targetFp;
  body["success"]        = success;
  body["message"]        = message;
  body["quality"]        = quality;
  String payload; serializeJson(body, payload);
  Serial.println("POST enroll-complete: " + payload);
  int code = http.POST(payload);
  Serial.printf("HTTP %d\n", code);
  if (code == 302) Serial.println("WARN: 302 — likely missing Accept header");
  http.end();

  delay(3000);
  backToIdle();
}

// ================= POLL =================
void pollServer() {
  if (WiFi.status() != WL_CONNECTED || serverBase.length() == 0) return;

  HTTPClient http;
  http.begin(serverBase + "/api/iot/poll");
  http.addHeader("Accept", "application/json");
  http.setTimeout(5000);
  int code = http.GET();
  if (code == 200) {
    String resp = http.getString();
    StaticJsonDocument<384> doc;
    if (!deserializeJson(doc, resp) && !doc["command"].isNull()) {
      String type = doc["command"]["type"] | "";
      long  cmdId = doc["command"]["id"]   | 0L;
      uint16_t fpid = doc["command"]["fingerprint_id"] | 0;
      Serial.printf("CMD: type=%s id=%ld fpid=%d\n", type.c_str(), cmdId, fpid);
      if (type == "enroll" && fpid > 0) {
        http.end();
        doEnrollment(cmdId, fpid);
        return;
      }
    }
  } else if (code < 0) {
    Serial.println("Poll lost, invalidate cache");
    invalidateCache();
  }
  http.end();
}

// ================= SETUP =================
void setup() {
  Serial.begin(115200);
  pinMode(BUZZER_PIN, OUTPUT);
  digitalWrite(BUZZER_PIN, LOW);

  Wire.begin(21, 22);
  lcd.init(); lcd.backlight();
  lcdPrint("Sistem Absensi", "Miftahul Ulum");
  delay(2000);

  connectWifi();

  if (WiFi.status() == WL_CONNECTED) {
    if (!discoverServer()) {
      lcdPrint("Server tdk", "ditemukan");
      beepError();
    }
    syncNtpTime();
    fetchPrayerSchedule();
  }

  mySerial.begin(57600, SERIAL_8N1, 16, 17);
  finger.begin(57600);
  lcdPrint("Cek Sensor...");
  delay(1000);

  if (finger.verifyPassword()) {
    Serial.println("Sensor OK");
    lcdPrint("Sensor OK");
    beepSuccess();
  } else {
    lcdPrint("Sensor Gagal", "Cek Wiring!"); beepError();
    while (true) delay(1000);
  }
  delay(1500);
  backToIdle();
}

// ================= LOOP =================
void loop() {
  if (WiFi.status() != WL_CONNECTED) { WiFi.reconnect(); delay(3000); }

  if (serverBase.length() == 0 && WiFi.status() == WL_CONNECTED) {
    discoverServer();
  }

  if (millis() - lastPollTime >= POLL_INTERVAL_MS) {
    lastPollTime = millis();
    pollServer();
  }

  // Refresh jadwal tiap 6 jam
  if (ntpSynced && (millis() - lastPrayerFetch >= PRAYER_REFRESH_MS)) {
    fetchPrayerSchedule();
  }

  uint8_t p = finger.getImage();
  if (p == FINGERPRINT_NOFINGER) {
    // Standby: tampil nama sholat aktif + jam realtime
    showIdleScreen();
    return;
  }
  if (p != FINGERPRINT_OK) {
    lcdPrint("Scan Gagal", "Coba Lagi"); beepError();
    delay(1500); backToIdle(); return;
  }
  if (finger.image2Tz() != FINGERPRINT_OK) {
    lcdPrint("Gagal Proses", "Angkat Jari"); beepError();
    delay(1500); backToIdle(); return;
  }

  // ─── Sensor cek match lokal (proses biometrik wajib di chip sensor) ──
  uint8_t searchResult = finger.fingerFastSearch();
  bool matched = (searchResult == FINGERPRINT_OK);
  uint16_t fpid = matched ? finger.fingerID : 0;
  int conf      = matched ? finger.confidence : 0;

  // Debounce hardware (anti spam sensor — bukan anti-fraud bisnis)
  if (millis() - lastScanTime < COOLDOWN_MS) return;
  lastScanTime = millis();

  // ═══ SEMUA RULE BISNIS DI SERVER ═══
  // ESP32 cuma kirim raw event scan. Server yang putuskan:
  //   - apakah di dalam waktu sholat
  //   - anti-fraud cooldown 5 menit per santri (via cache)
  //   - tap masuk vs tap keluar
  //   - lintas waktu sholat (Dzuhur → Ashar = hadir keduanya)
  if (matched) Serial.printf("Match: ID=%d, Conf=%d\n", fpid, conf);
  else         Serial.println("No match di sensor — tetap lapor ke server");
  kirimAbsensi(fpid, matched, conf);
}
