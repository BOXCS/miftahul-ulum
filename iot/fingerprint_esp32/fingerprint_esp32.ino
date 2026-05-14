/**
 * ================================================================
 * Miftahul Ulum — Fingerprint ESP32 ke VPS
 * ================================================================
 *
 * Versi ini TIDAK memakai auto-discovery subnet lokal.
 * Server langsung diarahkan ke VPS:
 * http://103.157.27.237:8000
 *
 * Endpoint ping yang sudah terbukti aktif:
 * http://103.157.27.237:8000/api/iot/ping
 */

#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <Adafruit_Fingerprint.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <time.h>

// ================= KONFIGURASI WIFI =================
#define WIFI_SSID    "LAB-MMC"
#define WIFI_PASS    ""

// ================= KONFIGURASI SERVER VPS =================
#define SERVER_BASE_URL "http://103.157.27.237:8000"
#define SERVER_SIG      "miftahul_ulum"

// ================= KONFIGURASI SISTEM =================
#define BUZZER_PIN       4
#define COOLDOWN_MS      3000
#define POLL_INTERVAL_MS 3000

// ================= NTP & JADWAL SHOLAT =================
// WIB = UTC+7. Untuk WITA gunakan 8 * 3600. Untuk WIT gunakan 9 * 3600.
#define GMT_OFFSET_SEC      (7 * 3600)
#define DST_OFFSET_SEC      0
#define NTP_SERVER_1        "pool.ntp.org"
#define NTP_SERVER_2        "time.google.com"

// Refresh jadwal sholat setiap 6 jam.
#define PRAYER_REFRESH_MS   (6UL * 60 * 60 * 1000)

// ================= HARDWARE =================
LiquidCrystal_I2C lcd(0x27, 16, 2);
HardwareSerial mySerial(2);
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);

String serverBase = SERVER_BASE_URL;

unsigned long lastScanTime = 0;
unsigned long lastPollTime = 0;
unsigned long lastPrayerFetch = 0;

bool ntpSynced = false;
bool serverOnline = false;

int consecutiveFailures = 0;
const int MAX_CONSECUTIVE_FAILURES = 5;

// ================= JADWAL SHOLAT =================
struct PrayerWindow {
  char name[12];
  int startMinOfDay;
  int endMinOfDay;
};

PrayerWindow todayPrayers[5];
int prayerCount = 0;
String prayerDateCached = "";

// Cache LCD idle agar tidak flicker.
String lastIdleTopLine = "";
String lastIdleBottomLine = "";

// ================= FORWARD DECLARATION =================
int currentPrayerIndex();
bool checkServer();

// ================= BUZZER =================
void beepSuccess() {
  digitalWrite(BUZZER_PIN, HIGH);
  delay(100);
  digitalWrite(BUZZER_PIN, LOW);
  delay(100);
  digitalWrite(BUZZER_PIN, HIGH);
  delay(100);
  digitalWrite(BUZZER_PIN, LOW);
}

void beepError() {
  digitalWrite(BUZZER_PIN, HIGH);
  delay(500);
  digitalWrite(BUZZER_PIN, LOW);
}

// ================= LCD HELPER =================
void lcdPrint(const String& l1, const String& l2 = "") {
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print(l1.substring(0, 16));
  lcd.setCursor(0, 1);
  lcd.print(l2.substring(0, 16));
}

void renderLcdLine(uint8_t row, const String& text, String& cache) {
  String padded = text;

  if (padded.length() > 16) {
    padded = padded.substring(0, 16);
  }

  while (padded.length() < 16) {
    padded += " ";
  }

  if (padded != cache) {
    lcd.setCursor(0, row);
    lcd.print(padded);
    cache = padded;
  }
}

void backToIdle() {
  lastIdleTopLine = "";
  lastIdleBottomLine = "";
}

void showIdleScreen() {
  String topLine;

  if (!serverOnline) {
    topLine = "Server offline";
  } else if (!ntpSynced) {
    topLine = "Sync waktu...";
  } else {
    int idx = currentPrayerIndex();
    if (idx >= 0) {
      topLine = String(todayPrayers[idx].name);
    } else {
      topLine = "Di luar sholat";
    }
  }

  String bottomLine = "Tunggu NTP";

  if (ntpSynced) {
    struct tm t;
    if (getLocalTime(&t)) {
      char buf[10];
      snprintf(buf, sizeof(buf), "%02d:%02d:%02d", t.tm_hour, t.tm_min, t.tm_sec);
      bottomLine = "    " + String(buf) + "    ";
    }
  }

  renderLcdLine(0, topLine, lastIdleTopLine);
  renderLcdLine(1, bottomLine, lastIdleBottomLine);
}

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
    delay(500);
    Serial.print(".");
    tries++;
  }

  Serial.println();

  if (WiFi.status() == WL_CONNECTED) {
    Serial.printf("WiFi OK: %s\n", WiFi.localIP().toString().c_str());
    lcdPrint("WiFi OK", WiFi.localIP().toString());
    beepSuccess();
  } else {
    Serial.println("WiFi gagal");
    lcdPrint("WiFi Gagal", "Cek Setting");
    beepError();
  }

  delay(1500);
}

// ================= SERVER VPS =================
bool checkServer() {
  if (WiFi.status() != WL_CONNECTED) {
    serverOnline = false;
    return false;
  }

  HTTPClient http;
  String url = serverBase + "/api/iot/ping";

  Serial.println("Cek server:");
  Serial.println(url);

  http.begin(url);
  http.addHeader("Accept", "application/json");
  http.setTimeout(8000);

  int code = http.GET();

  Serial.print("Ping HTTP code: ");
  Serial.println(code);

  bool ok = false;

  if (code == 200) {
    String resp = http.getString();
    Serial.println("Ping response:");
    Serial.println(resp);

    StaticJsonDocument<256> doc;
    DeserializationError err = deserializeJson(doc, resp);

    if (!err) {
      String sig = doc["server"] | "";
      ok = (sig == SERVER_SIG);
    }
  }

  http.end();

  serverOnline = ok;

  if (ok) {
    lcdPrint("Server VPS OK", "Port 8000");
    beepSuccess();
    delay(1500);
  } else {
    lcdPrint("Server Gagal", "Cek VPS/API");
    beepError();
    delay(2000);
  }

  return ok;
}

// ================= NTP TIME SYNC =================
void syncNtpTime() {
  Serial.println("NTP sync...");
  lcdPrint("Sync waktu...", "NTP");

  configTime(GMT_OFFSET_SEC, DST_OFFSET_SEC, NTP_SERVER_1, NTP_SERVER_2);

  struct tm t;

  if (getLocalTime(&t, 10000)) {
    Serial.printf(
      "Time OK: %04d-%02d-%02d %02d:%02d:%02d\n",
      t.tm_year + 1900,
      t.tm_mon + 1,
      t.tm_mday,
      t.tm_hour,
      t.tm_min,
      t.tm_sec
    );

    ntpSynced = true;

    char buf[20];
    snprintf(
      buf,
      sizeof(buf),
      "%02d:%02d %02d-%02d-%02d",
      t.tm_hour,
      t.tm_min,
      t.tm_mday,
      t.tm_mon + 1,
      (t.tm_year + 1900) % 100
    );

    lcdPrint("NTP Sync OK", buf);
  } else {
    Serial.println("NTP gagal");
    lcdPrint("NTP Gagal", "Pakai lokal");
    ntpSynced = false;
  }

  delay(1500);
}

// ================= JADWAL SHOLAT =================
int parseTimeToMin(const String& s) {
  int h = s.substring(0, 2).toInt();
  int m = s.substring(3, 5).toInt();

  return h * 60 + m;
}

bool fetchPrayerSchedule() {
  if (WiFi.status() != WL_CONNECTED || serverBase.length() == 0) {
    return false;
  }

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
    if (prayerCount >= 5) {
      break;
    }

    const char* name = w["name"] | "";
    String startStr = String((const char*)(w["start"] | "00:00:00"));
    String endStr = String((const char*)(w["end"] | "00:00:00"));

    strncpy(todayPrayers[prayerCount].name, name, sizeof(todayPrayers[prayerCount].name) - 1);
    todayPrayers[prayerCount].name[sizeof(todayPrayers[prayerCount].name) - 1] = '\0';

    todayPrayers[prayerCount].startMinOfDay = parseTimeToMin(startStr);
    todayPrayers[prayerCount].endMinOfDay = parseTimeToMin(endStr);

    Serial.printf("  %s: %s - %s\n", name, startStr.c_str(), endStr.c_str());

    prayerCount++;
  }

  lastPrayerFetch = millis();

  Serial.printf("%d window jadwal sholat dimuat\n", prayerCount);

  return prayerCount > 0;
}

int currentPrayerIndex() {
  if (!ntpSynced || prayerCount == 0) {
    return -1;
  }

  struct tm t;

  if (!getLocalTime(&t)) {
    return -1;
  }

  char todayBuf[12];

  snprintf(
    todayBuf,
    sizeof(todayBuf),
    "%04d-%02d-%02d",
    t.tm_year + 1900,
    t.tm_mon + 1,
    t.tm_mday
  );

  if (prayerDateCached != String(todayBuf)) {
    Serial.println("Hari berganti, fetch ulang jadwal");
    fetchPrayerSchedule();

    if (prayerCount == 0) {
      return -1;
    }
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
void kirimAbsensi(uint16_t fingerprintId, bool matched, int confidence) {
  if (WiFi.status() != WL_CONNECTED) {
    lcdPrint("Offline!", "Cek WiFi");
    beepError();
    delay(2000);
    backToIdle();
    return;
  }

  if (!serverOnline) {
    lcdPrint("Server offline", "Cek koneksi");
    beepError();
    delay(2000);
    backToIdle();
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
    Serial.println(resp);

    StaticJsonDocument<512> doc;

    if (!deserializeJson(doc, resp)) {
      String nama = doc["student_name"] | "";
      String waktu = doc["waktu_shalat"] | "";
      String status = doc["status"] | "";
      String action = doc["action"] | "";

      String line2;

      if (action == "keluar") {
        line2 = "Keluar " + waktu;
      } else if (action == "keluar_lintas") {
        line2 = "Lintas->" + waktu;
      } else {
        line2 = waktu + " " + status;
      }

      lcdPrint(nama, line2);

      if (status == "terlambat") {
        beepError();
      } else {
        beepSuccess();
      }
    } else {
      lcdPrint("JSON Error");
      beepError();
    }
  } else if (code == 429) {
    String resp = http.getString();

    StaticJsonDocument<256> doc;
    deserializeJson(doc, resp);

    String msg = doc["message"] | "Cooldown";

    lcdPrint("Tunggu", msg.substring(0, 16));
    beepError();
  } else if (code == 404) {
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
  } else if (code == 422) {
    String resp = http.getString();

    Serial.println("422 body:");
    Serial.println(resp);

    lcdPrint("Di luar", "Waktu Shalat");
    beepError();
  } else if (code == 302) {
    Serial.println("302 redirect. Cek header Laravel.");
    lcdPrint("Server 302", "Cek header");
    beepError();
  } else if (code < 0) {
    consecutiveFailures++;

    Serial.printf(
      "Connection fail %d/%d code=%d\n",
      consecutiveFailures,
      MAX_CONSECUTIVE_FAILURES,
      code
    );

    if (consecutiveFailures >= MAX_CONSECUTIVE_FAILURES) {
      serverOnline = false;
      consecutiveFailures = 0;

      lcdPrint("Server lost", "Cek VPS");
      checkServer();
    } else {
      lcdPrint("Server lambat", "Retry...");
    }

    beepError();
  } else {
    lcdPrint("Server Error", "HTTP:" + String(code));
    beepError();
  }

  if (code >= 200) {
    consecutiveFailures = 0;
  }

  http.end();

  delay(3000);
  backToIdle();
}

// ================= ENROLLMENT =================
int capturePrint(unsigned long timeoutMs) {
  unsigned long start = millis();

  while ((millis() - start) < timeoutMs) {
    if (finger.getImage() == FINGERPRINT_OK) {
      return FINGERPRINT_OK;
    }

    delay(50);
  }

  return -1;
}

void doEnrollment(long commandId, uint16_t targetFp) {
  Serial.printf("\n=== ENROLL id=%d ===\n", targetFp);

  bool success = false;
  String message = "";
  int quality = 0;

  lcdPrint("Daftar 1/2", "Tempel jari");
  beepSuccess();

  if (capturePrint(15000) != FINGERPRINT_OK) {
    message = "Timeout 1";
    goto report;
  }

  if (finger.image2Tz(1) != FINGERPRINT_OK) {
    message = "Img2Tz #1 fail";
    goto report;
  }

  lcdPrint("Angkat Jari");
  delay(2000);

  while (finger.getImage() != FINGERPRINT_NOFINGER) {
    delay(100);
  }

  lcdPrint("Daftar 2/2", "Tempel ulang");

  if (capturePrint(15000) != FINGERPRINT_OK) {
    message = "Timeout 2";
    goto report;
  }

  if (finger.image2Tz(2) != FINGERPRINT_OK) {
    message = "Img2Tz #2 fail";
    goto report;
  }

  if (finger.createModel() != FINGERPRINT_OK) {
    message = "Jari tdk match";
    goto report;
  }

  if (finger.storeModel(targetFp) != FINGERPRINT_OK) {
    message = "Simpan gagal";
    goto report;
  }

  success = true;
  quality = 95;
  message = "Berhasil";

  lcdPrint("Berhasil!", "ID: #" + String(targetFp));
  beepSuccess();

report:
  if (!success) {
    lcdPrint("Enroll Gagal", message);
    beepError();

    finger.deleteModel(targetFp);

    Serial.printf("Slot %d dihapus utk sinkron dgn DB\n", targetFp);
  }

  if (WiFi.status() != WL_CONNECTED || !serverOnline) {
    Serial.println("Gagal kirim enroll-complete karena server offline");
    delay(3000);
    backToIdle();
    return;
  }

  HTTPClient http;
  http.begin(serverBase + "/api/iot/enroll-complete");
  setupJsonHeaders(http);
  http.setTimeout(8000);

  StaticJsonDocument<256> body;

  body["command_id"] = commandId;
  body["fingerprint_id"] = targetFp;
  body["success"] = success;
  body["message"] = message;
  body["quality"] = quality;

  String payload;
  serializeJson(body, payload);

  Serial.println("POST enroll-complete:");
  Serial.println(payload);

  int code = http.POST(payload);

  Serial.printf("HTTP %d\n", code);

  if (code == 302) {
    Serial.println("WARN: 302. Cek Accept header.");
  }

  http.end();

  delay(3000);
  backToIdle();
}

// ================= POLL COMMAND DARI SERVER =================
void pollServer() {
  if (WiFi.status() != WL_CONNECTED || !serverOnline) {
    return;
  }

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
      long cmdId = doc["command"]["id"] | 0L;
      uint16_t fpid = doc["command"]["fingerprint_id"] | 0;

      Serial.printf(
        "CMD: type=%s id=%ld fpid=%d\n",
        type.c_str(),
        cmdId,
        fpid
      );

      if (type == "enroll" && fpid > 0) {
        http.end();
        doEnrollment(cmdId, fpid);
        return;
      }
    }
  } else if (code < 0) {
    Serial.printf("Poll gagal code=%d\n", code);

    consecutiveFailures++;

    if (consecutiveFailures >= MAX_CONSECUTIVE_FAILURES) {
      serverOnline = false;
      consecutiveFailures = 0;
      checkServer();
    }
  }

  http.end();
}

// ================= SETUP =================
void setup() {
  Serial.begin(115200);

  pinMode(BUZZER_PIN, OUTPUT);
  digitalWrite(BUZZER_PIN, LOW);

  Wire.begin(21, 22);

  lcd.init();
  lcd.backlight();

  lcdPrint("Sistem Absensi", "Miftahul Ulum");
  delay(2000);

  connectWifi();

  if (WiFi.status() == WL_CONNECTED) {
    serverBase = SERVER_BASE_URL;

    Serial.println("Server VPS digunakan:");
    Serial.println(serverBase);

    checkServer();

    if (serverOnline) {
      syncNtpTime();
      fetchPrayerSchedule();
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
    Serial.println("Sensor gagal. Cek wiring.");
    lcdPrint("Sensor Gagal", "Cek Wiring!");
    beepError();

    while (true) {
      delay(1000);
    }
  }

  delay(1500);
  backToIdle();
}

// ================= LOOP =================
void loop() {
  if (WiFi.status() != WL_CONNECTED) {
    serverOnline = false;

    lcdPrint("WiFi putus", "Reconnect...");
    WiFi.reconnect();

    delay(3000);

    if (WiFi.status() == WL_CONNECTED) {
      lcdPrint("WiFi OK", WiFi.localIP().toString());
      delay(1000);
      checkServer();

      if (serverOnline && !ntpSynced) {
        syncNtpTime();
      }
    }

    backToIdle();
    return;
  }

  if (!serverOnline) {
    static unsigned long lastServerCheck = 0;

    if (millis() - lastServerCheck >= 10000) {
      lastServerCheck = millis();
      checkServer();

      if (serverOnline) {
        syncNtpTime();
        fetchPrayerSchedule();
      }

      backToIdle();
    }

    showIdleScreen();
    return;
  }

  if (millis() - lastPollTime >= POLL_INTERVAL_MS) {
    lastPollTime = millis();
    pollServer();
  }

  if (ntpSynced && (millis() - lastPrayerFetch >= PRAYER_REFRESH_MS)) {
    fetchPrayerSchedule();
  }

  uint8_t p = finger.getImage();

  if (p == FINGERPRINT_NOFINGER) {
    showIdleScreen();
    return;
  }

  if (p != FINGERPRINT_OK) {
    lcdPrint("Scan Gagal", "Coba Lagi");
    beepError();
    delay(1500);
    backToIdle();
    return;
  }

  if (finger.image2Tz() != FINGERPRINT_OK) {
    lcdPrint("Gagal Proses", "Angkat Jari");
    beepError();
    delay(1500);
    backToIdle();
    return;
  }

  uint8_t searchResult = finger.fingerFastSearch();

  bool matched = (searchResult == FINGERPRINT_OK);
  uint16_t fpid = matched ? finger.fingerID : 0;
  int conf = matched ? finger.confidence : 0;

  if (millis() - lastScanTime < COOLDOWN_MS) {
    return;
  }

  lastScanTime = millis();

  if (matched) {
    Serial.printf("Match: ID=%d, Conf=%d\n", fpid, conf);
  } else {
    Serial.println("No match di sensor. Tetap lapor ke server.");
  }

  kirimAbsensi(fpid, matched, conf);
}