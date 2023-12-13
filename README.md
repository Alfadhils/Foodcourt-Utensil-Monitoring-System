# Foodcourt Utensil Monitoring System

## Overview

The Foodcourt Utensil Monitoring System is a robust solution designed to efficiently track and manage the usage of utensils within a foodcourt environment. The system utilizes RFID (Radio-Frequency Identification) tags to uniquely identify and monitor each utensil, providing real-time data on their usage patterns. This README document provides an overview of the system's architecture, installation process, and usage instructions.

## Features

- **RFID Tag Integration:** Utensils are equipped with RFID tags for accurate and efficient tracking.
- **Dedicated Database:** Utilizes a dedicated database to store and manage utensil usage data.
- **Web Interface:** A user-friendly web interface provides real-time information on utensil status and usage patterns.
- **XAMPP Stack:** The system is built on the XAMPP stack for easy deployment and cross-platform compatibility.

## System Architecture

The Foodcourt Utensil Monitoring System consists of the following components:

1. **ESP32 RFID Reader Module:** This component is built using the ESP32 microcontroller. It efficiently captures RFID tag information and manages the packet transfer to the web server.
2. **Database Server:** Manages the storage and retrieval of utensil usage data.
3. **Web Server:** Hosts the web interface for users to monitor utensil status and generate reports.
