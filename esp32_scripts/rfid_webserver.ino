#include <Arduino.h>
#include <ArduinoJson.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>
#include <LiquidCrystal_I2C.h>

const char* ssid = "Galaxy A51F2BC";
const char* password = "osly8876";

#define SS_PIN 5
#define RST_PIN 15

LiquidCrystal_I2C lcd(0x27, 16, 2);

int buzzer = 27;
int tambah = 26;
int masuk = 25;
int keluar = 33;

bool data_tambah = 0;
bool data_masuk = 0;
bool data_keluar = 0;
#define ditekan 0
int val = 0;

MFRC522 rfid(SS_PIN, RST_PIN);

String id;

void setup() {
  Serial.begin(115200);
  SPI.begin();
  rfid.PCD_Init();
  lcd.begin();
  lcd.backlight();
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  
  lcd.setCursor(1, 0);
  lcd.print("Sistem Monitoring");
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(100);
    Serial.println("Connecting to WiFi..");
    lcd.setCursor(0, 1);
    lcd.print("Inisialisasi..");
  }

  Serial.println(WiFi.localIP());
  
  lcd.clear();
  lcd.setCursor(1, 0);
  lcd.print("Sistem Monitoring");
  lcd.setCursor(0, 1);
  lcd.print("WiFi Connected");

  pinMode(buzzer, OUTPUT);
  pinMode(tambah, INPUT_PULLUP);
  pinMode(masuk, INPUT_PULLUP);
  pinMode(keluar, INPUT_PULLUP);
}
 
void loop() {
  data_tambah = digitalRead(tambah);
  data_masuk = digitalRead(masuk);
  data_keluar = digitalRead(keluar);

  if (data_tambah == ditekan) {
    Serial.println("add");
    val = 1;
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Data Add Mode");
    lcd.setCursor(0, 1);
    lcd.print("Put Card");
  }

  if (data_masuk == ditekan) {
    Serial.println("in");
    val = 2;
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Update Mode");
    lcd.setCursor(0, 1);
    lcd.print("Put Card");
  }

  if (data_keluar == ditekan) {
    Serial.println("out");
    val = 3;
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Check Mode");
    lcd.setCursor(0, 1);
    lcd.print("Put Card");
  }

switch (val) {
    case 1:
      if (rfid.PICC_IsNewCardPresent() && rfid.PICC_ReadCardSerial()) {
        processCard("Data Add Mode");
      }
      break;
    case 2:
      if (rfid.PICC_IsNewCardPresent() && rfid.PICC_ReadCardSerial()) {
        processCard("Update Mode");
      }
      break;
    case 3:
      if (rfid.PICC_IsNewCardPresent() && rfid.PICC_ReadCardSerial()) {
        processCard("Check Mode");
      }
      break;
  }
}

void processCard(const char* mode) {
  digitalWrite(buzzer, HIGH);
  delay(200);
  digitalWrite(buzzer, LOW);

  Serial.println();
  Serial.print(F("In dec: "));

  for (byte i = 0; i < rfid.uid.size; i++) {
    id += (rfid.uid.uidByte[i] < 0x10 ? "0" : "") + String(rfid.uid.uidByte[i], DEC);
  }

  id.toUpperCase();
  rfid.PICC_HaltA();
  rfid.PCD_StopCrypto1();
  Serial.print(id);

  switch (val) {
    case 1:
      send_data();
      break;
    case 2:
      send_datain();
      break;
    case 3:
      send_dataout();
      break;
  }

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print(mode);
  lcd.setCursor(0, 1);
  lcd.print(id);
  id = "";
  delay(1000);
  lcd.setCursor(0, 0);
  lcd.print(mode);
  lcd.setCursor(0, 1);
  lcd.print("Put Card");
}

void send_data() {
  String datareq = "http://192.168.17.61/webserver/add_tag.php?";
  datareq += "tag=";
  datareq += id;
  sendHttpRequest(datareq);
}

void send_datain() {
  String datareq = "http://192.168.17.61/webserver/update_tag.php?";
  datareq += "tag=";
  datareq += id;
  datareq += "&ket=";
  datareq += "Masuk";
  sendHttpRequest(datareq);
}

void send_dataout() {
  String datareq = "http://192.168.17.61/webserver/check_tag.php?";
  datareq += "tag=";
  datareq += id;
  datareq += "&ket=";
  datareq += "Keluar";
  sendHttpRequest(datareq);
}

void sendHttpRequest(String datareq) {
  Serial.println(datareq);
  HTTPClient http;
  http.begin(datareq);
  int httpCode = http.GET();

  if (httpCode > 0) {
    Serial.printf("[HTTP] GET... code: %d\n", httpCode);

    if (httpCode == HTTP_CODE_OK) {
      String payload = http.getString();
      Serial.println(payload);
    } else {
      Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
    }

    http.end();
  }
}
