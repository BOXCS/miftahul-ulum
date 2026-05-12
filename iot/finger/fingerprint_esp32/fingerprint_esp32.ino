/**
 * ================================================================
 * Miftahul Ulum — Fingerprint ESP32 (auto-discover server)
 * ================================================================
 *
 * Fitur baru: auto-discovery IP server Laravel di subnet WiFi yang
 *             sama. Tidak perlu hardcode IP. Hasil di-cache di NVS.
 *
 * Alur discovery:
 *   1. Load IP cached dari Preferences (NVS)
 *   2. Probe http://cached:8000/api/iot/ping → kalau OK, pakai
 *   3. Kalau gagal, scan subnet (gateway-prioritized) sampai ketemu
 *   4. Server respond {"server":"miftahul_ulum"} → simpan, lanjut
 */

#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <Adafruit_Fingerprint.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <Preferences.h>

// ================= KONFIGURASI WIFI =================
#define WIFI_SSID    "Basecamp 1"
#define WIFI_PASS    "gulaaren"

#define SERVER_PORT  8000
#define SERVER_SIG   "miftahul_ulum"   // signature unik dari /api/iot/ping

#define BUZZER_PIN       4
#define COOLDOWN_MS      3000
#define POLL_INTERVAL_MS 3000

// ================= HARDWARE =================
LiquidCrystal_I2C lcd(0x27, 16, 2);
HardwareSerial mySerial(2);
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);
Preferences prefs;

// State runtime
String serverBase = "";       // diisi otomatis setelah discovery
unsigned long lastScanTime = 0;
unsigned long lastPollTime = 0;

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

/**
 * Probe satu IP. Return true kalau server signature match.
 * Timeout pendek (300ms) supaya scan cepat.
 */
bool probeHost(const String& ip) {
  HTTPClient http;
  String url = "http://" + ip + ":" + String(SERVER_PORT) + "/api/iot/ping";
  http.begin(url);
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

/**
 * Cari IP server di subnet:
 *   1. Coba IP cached
 *   2. Coba gateway (router) — kadang server di router host
 *   3. Coba kandidat umum dulu (.1..30, .100..150)
 *   4. Full scan .1..254
 */
bool discoverServer() {
  lcdPrint("Cari server...", "Mode auto");
  Serial.println("\n=== DISCOVERY ===");

  IPAddress me  = WiFi.localIP();
  IPAddress gw  = WiFi.gatewayIP();
  String prefix = String(me[0]) + "." + me[1] + "." + me[2] + "."; // misal "192.168.1."

  // 1. Coba IP cached
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

  // 2. Coba gateway IP
  String gwIp = String(gw[0]) + "." + gw[1] + "." + gw[2] + "." + gw[3];
  Serial.printf("Try gateway: %s\n", gwIp.c_str());
  if (probeHost(gwIp)) {
    serverBase = "http://" + gwIp + ":" + String(SERVER_PORT);
    saveServerIp(gwIp);
    return true;
  }

  // 3. Kandidat umum dulu (lebih cepat ketemu)
  uint8_t priority[] = {
    1, 2, 3, 4, 5, 10, 100, 101, 102, 103, 104, 105,
    110, 120, 150, 200,
    // sisanya di full scan
  };
  for (uint8_t i : priority) {
    if (i == me[3]) continue;  // skip ESP32 sendiri
    String ip = prefix + String(i);
    lcdPrint("Scan...", ip);
    Serial.print("."); Serial.print(i); Serial.print(" ");
    if (probeHost(ip)) {
      Serial.println();
      serverBase = "http://" + ip + ":" + String(SERVER_PORT);
      saveServerIp(ip);
      return true;
    }
  }

  // 4. Full scan .1..254 (skip yang sudah dicoba)
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

void saveServerIp(const String& ip) {
  prefs.begin("iot", false);
  prefs.putString("server_ip", ip);
  prefs.end();
  Serial.println("✓ Server: " + ip + " (cached)");
  lcdPrint("Server OK", ip);
  beepSuccess();
  delay(1500);
}

// ================= KIRIM ABSENSI =================
void kirimAbsensi(uint16_t fingerprintId) {
  if (WiFi.status() != WL_CONNECTED || serverBase.length() == 0) {
    lcdPrint("Offline!", "Cek WiFi");
    beepError(); delay(2000); lcdPrint("Tempel Jari");
    return;
  }

  lcdPrint("Mengirim...");
  HTTPClient http;
  http.begin(serverBase + "/api/absensi/fingerprint");
  http.addHeader("Content-Type", "application/json");
  http.setTimeout(8000);

  String body = "{\"fingerprint_id\":" + String(fingerprintId) + "}";
  int code = http.POST(body);
  Serial.printf("HTTP %d\n", code);

  if (code == 200 || code == 201) {
    String resp = http.getString();
    StaticJsonDocument<512> doc;
    if (!deserializeJson(doc, resp)) {
      String nama   = doc["student_name"] | "";
      String waktu  = doc["waktu_shalat"]  | "";
      String status = doc["status"]        | "";
      lcdPrint(nama, waktu + " " + status);
      (status == "terlambat") ? beepError() : beepSuccess();
    } else { lcdPrint("JSON Error"); beepError(); }
  } else if (code == 429) { lcdPrint("Tunggu", "Cooldown"); beepError(); }
  else if (code == 404) { lcdPrint("Akses Ditolak", "Belum Daftar"); beepError(); }
  else if (code == 422) { lcdPrint("Di luar", "Waktu Shalat"); beepError(); }
  else if (code < 0) {
    // Connection lost → invalidate cache, akan re-discover di siklus berikutnya
    Serial.println("Connection lost, invalidate cache");
    prefs.begin("iot", false); prefs.remove("server_ip"); prefs.end();
    serverBase = "";
    lcdPrint("Server lost", "Mencari lagi..");
    beepError();
  } else { lcdPrint("Server Error", "HTTP:" + String(code)); beepError(); }

  http.end();
  delay(3000);
  lcdPrint("Tempel Jari");
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
  if (!success) { lcdPrint("Enroll Gagal", message); beepError(); }

  HTTPClient http;
  http.begin(serverBase + "/api/iot/enroll-complete");
  http.addHeader("Content-Type", "application/json");
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
  http.end();

  delay(3000);
  lcdPrint("Tempel Jari");
}

// ================= POLL =================
void pollServer() {
  if (WiFi.status() != WL_CONNECTED || serverBase.length() == 0) return;

  HTTPClient http;
  http.begin(serverBase + "/api/iot/poll");
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
    // Server lost → invalidate cache
    prefs.begin("iot", false); prefs.remove("server_ip"); prefs.end();
    serverBase = "";
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

  // Discovery server
  if (WiFi.status() == WL_CONNECTED) {
    if (!discoverServer()) {
      lcdPrint("Server tdk", "ditemukan");
      beepError();
    }
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
  lcdPrint("Tempel Jari");
}

// ================= LOOP =================
void loop() {
  if (WiFi.status() != WL_CONNECTED) { WiFi.reconnect(); delay(3000); }

  // Re-discover kalau server lost
  if (serverBase.length() == 0 && WiFi.status() == WL_CONNECTED) {
    discoverServer();
  }

  // Poll command (3 detik)
  if (millis() - lastPollTime >= POLL_INTERVAL_MS) {
    lastPollTime = millis();
    pollServer();
  }

  // Mode scan absensi
  uint8_t p = finger.getImage();
  if (p == FINGERPRINT_NOFINGER) return;
  if (p != FINGERPRINT_OK) {
    lcdPrint("Scan Gagal", "Coba Lagi"); beepError();
    delay(1500); lcdPrint("Tempel Jari"); return;
  }
  if (finger.image2Tz() != FINGERPRINT_OK) {
    lcdPrint("Gagal Proses", "Angkat Jari"); beepError();
    delay(1500); lcdPrint("Tempel Jari"); return;
  }
  if (finger.fingerFastSearch() != FINGERPRINT_OK) {
    lcdPrint("Akses Ditolak", "Tidak Dikenal"); beepError();
    delay(2500); lcdPrint("Tempel Jari"); return;
  }

  if (millis() - lastScanTime < COOLDOWN_MS) return;
  lastScanTime = millis();

  Serial.printf("Match: ID=%d, Conf=%d\n", finger.fingerID, finger.confidence);
  kirimAbsensi(finger.fingerID);
}
