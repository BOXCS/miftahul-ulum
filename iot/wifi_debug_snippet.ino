// ─────────────────────────────────────────────────────────────
// PASTE ini ke kode ESP32 Anda, GANTIKAN fungsi connectWifi()
// dan tambahkan event handler-nya.
// Tujuan: print kenapa WiFi gagal connect (alasan spesifik).
// ─────────────────────────────────────────────────────────────

#include <WiFi.h>

// Helper: terjemahkan kode disconnect ke teks manusiawi
const char* wifiDisconnectReason(uint8_t reason) {
  switch (reason) {
    case  1: return "UNSPECIFIED";
    case  2: return "AUTH_EXPIRE";
    case  3: return "AUTH_LEAVE";
    case  4: return "ASSOC_EXPIRE";
    case  5: return "ASSOC_TOOMANY";
    case  6: return "NOT_AUTHED";
    case  7: return "NOT_ASSOCED";
    case  8: return "ASSOC_LEAVE";
    case 15: return "4WAY_HANDSHAKE_TIMEOUT (password salah?)";
    case 16: return "GROUP_KEY_UPDATE_TIMEOUT";
    case 200: return "BEACON_TIMEOUT (router tidak respons)";
    case 201: return "NO_AP_FOUND (SSID tidak ada di sekitar / 5GHz only)";
    case 202: return "AUTH_FAIL (password salah / WPA3 incompat)";
    case 203: return "ASSOC_FAIL";
    case 204: return "HANDSHAKE_TIMEOUT (password salah)";
    case 205: return "CONNECTION_FAIL";
    default:  return "UNKNOWN";
  }
}

// Event handler — tampilkan alasan setiap kali disconnect
void onWifiEvent(WiFiEvent_t event, WiFiEventInfo_t info) {
  if (event == ARDUINO_EVENT_WIFI_STA_DISCONNECTED) {
    uint8_t r = info.wifi_sta_disconnected.reason;
    Serial.printf("❌ WiFi DISCONNECT reason=%d (%s)\n", r, wifiDisconnectReason(r));
  } else if (event == ARDUINO_EVENT_WIFI_STA_GOT_IP) {
    Serial.printf("✅ Got IP: %s\n", WiFi.localIP().toString().c_str());
  }
}

void scanNetworks() {
  Serial.println("\n🔍 Scan WiFi sekitar...");
  int n = WiFi.scanNetworks();
  if (n == 0) {
    Serial.println("   (tidak ada SSID terdeteksi)");
    return;
  }
  for (int i = 0; i < n; i++) {
    Serial.printf("   %2d) %-32s  RSSI=%4d dBm  Ch=%2d  Enc=%d\n",
      i + 1,
      WiFi.SSID(i).c_str(),
      WiFi.RSSI(i),
      WiFi.channel(i),
      WiFi.encryptionType(i)
    );
  }
  Serial.println();
}

void connectWifi() {
  lcdPrint("Konek WiFi...", WIFI_SSID);
  Serial.printf("\n=== Connect ke '%s' ===\n", WIFI_SSID);

  WiFi.disconnect(true);   // bersihkan kredensial lama
  delay(500);
  WiFi.mode(WIFI_STA);
  WiFi.setSleep(false);    // matikan power-save → koneksi lebih stabil
  WiFi.onEvent(onWifiEvent);

  // Scan dulu untuk konfirmasi SSID terlihat
  scanNetworks();

  WiFi.begin(WIFI_SSID, WIFI_PASS);

  Serial.print("Menunggu koneksi");
  int tries = 0;
  while (WiFi.status() != WL_CONNECTED && tries < 40) {  // 20 detik (40 × 500ms)
    delay(500);
    Serial.print(".");
    tries++;
  }
  Serial.println();

  if (WiFi.status() == WL_CONNECTED) {
    Serial.printf("✅ WiFi OK: %s  RSSI=%d dBm\n",
      WiFi.localIP().toString().c_str(), WiFi.RSSI());
    lcdPrint("WiFi OK", WiFi.localIP().toString().c_str());
    beepSuccess();
  } else {
    Serial.printf("❌ GAGAL setelah %d detik. Status code: %d\n", tries / 2, WiFi.status());
    // Status code:
    //   0 = IDLE, 1 = NO_SSID_AVAIL, 3 = CONNECTED, 4 = CONNECT_FAILED,
    //   6 = DISCONNECTED, 7 = WL_CONNECT_WRONG_PASSWORD (ESP32 v2.x)
    lcdPrint("WiFi Gagal", "Cek Serial");
    beepError();
  }
  delay(2000);
}
