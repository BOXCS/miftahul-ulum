# Setup Notifikasi Pengumuman (FCM)

Saat admin publish pengumuman di web, semua orang tua yang sudah login di app Flutter akan otomatis menerima notifikasi.

---

## Yang Sudah Jadi di Backend

- ✅ Firebase credentials tersimpan di `storage/app/firebase-credentials.json`
- ✅ FCM terkirim otomatis saat admin publish pengumuman
- ✅ Kolom `fcm_token` sudah ada di tabel `parents`
- ✅ Endpoint untuk simpan token sudah tersedia

---

## Yang Perlu Dilakukan di Flutter

### 1. Tambah dependency di `pubspec.yaml`

```yaml
dependencies:
  firebase_core: latest
  firebase_messaging: latest
```

### 2. Taruh file `google-services.json` di `android/app/`

Download dari Firebase Console → Project Settings → General → Your apps.

### 3. Inisialisasi FCM setelah login

```dart
import 'package:firebase_messaging/firebase_messaging.dart';

Future<void> initFCM(String authToken) async {
  await Firebase.initializeApp();

  // Minta izin notifikasi
  await FirebaseMessaging.instance.requestPermission();

  // Ambil token lalu kirim ke backend
  String? token = await FirebaseMessaging.instance.getToken();
  if (token != null) {
    await kirimTokenKeBackend(token, authToken);
  }

  // Kalau token berubah, kirim ulang
  FirebaseMessaging.instance.onTokenRefresh.listen((newToken) {
    kirimTokenKeBackend(newToken, authToken);
  });
}
```

### 4. Kirim token ke backend

```dart
Future<void> kirimTokenKeBackend(String fcmToken, String authToken) async {
  await http.post(
    Uri.parse('http://BASE_URL/api/fcm-token'),
    headers: {
      'Authorization': 'Bearer $authToken',
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    },
    body: jsonEncode({'fcm_token': fcmToken}),
  );
}
```

> Panggil `initFCM()` tepat setelah login berhasil dan token disimpan.

### 5. Handle notifikasi masuk

```dart
// Saat app terbuka (foreground)
FirebaseMessaging.onMessage.listen((RemoteMessage message) {
  print('Judul: ${message.notification?.title}');
  print('Isi: ${message.notification?.body}');

  // Cek tipe notifikasi
  if (message.data['type'] == 'announcement') {
    // navigasi ke halaman pengumuman
  }
});

// Saat user tap notifikasi (background/terminated)
FirebaseMessaging.onMessageOpenedApp.listen((RemoteMessage message) {
  if (message.data['type'] == 'announcement') {
    // navigasi ke halaman pengumuman
  }
});
```

---

## Format Notifikasi yang Diterima

```json
{
  "notification": {
    "title": "Pengumuman Baru: Libur Hari Raya",
    "body": "Seluruh santri diliburkan mulai tanggal..."
  },
  "data": {
    "type": "announcement",
    "id": "5"
  }
}
```

Gunakan `data.id` untuk fetch detail pengumuman via `GET /api/pengumuman`.

---

## Alur Lengkap

```
Orang tua login di Flutter
        ↓
Flutter ambil FCM token dari Firebase
        ↓
Flutter kirim token ke POST /api/fcm-token
        ↓
Admin publish pengumuman di web
        ↓
Backend kirim notifikasi ke semua token
        ↓
HP orang tua menerima notifikasi
```
