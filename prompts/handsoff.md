# Agent Handoff Document

## Current Project State (Updated June 26, 2026)

We have built the architecture to send automated WhatsApp fee reminders to students using the unofficial **Evolution API**, bypassing Meta's official API constraints (no templates, no per-message fees). The setup is split into two parts:

### 1. Evolution API (AWS Server)
- **Status:** Installed and running via Docker Compose. **(Port 8080 is OPEN)**
- **Server:** AWS EC2 Instance (Upgraded to **`t3.small`** to resolve memory crashes).
- **Endpoint:** `http://16.171.12.145:8080` *(IP Address changed due to instance upgrade)*
- **Global Token:** `ee0695acb2cc6aa0c0f96556bca82317ac7fa8b85ed91de9c7849c0f1f579356` *(Corrected from the previous agent's notes)*
- **Instance Name:** `tenstrings-alerts`
- **Recent Fixes:**
  - Added a `redis` container to the Evolution API `docker-compose.yml` to resolve continuous crashing loops.
  - Upgraded the EC2 instance from `t3.micro` (1GB) to `t3.small` (2GB) because the internal Baileys engine was crashing from lack of memory when generating the QR code.
  - The API manager is available natively at `http://16.171.12.145:8080/manager` to scan the QR Code.

### 2. Laravel Portal App (Hostinger / GitHub)
- **Status:** Code is written, committed, and pushed to `main`.
- **Files Created:**
  - `app/Jobs/SendWhatsAppMessage.php`: A queued job that posts to the Evolution API endpoint `message/sendText`.
  - `app/Console/Commands/SendFeeReminders.php`: An Artisan command (`app:send-fee-reminders`) that dispatches the Job with a cumulative staggered delay.
- **Pending Tasks on Hostinger (CRITICAL FOR NEXT AGENT):** 
  1. Update `.env` on Hostinger with the NEW IP and NEW Token:
     ```env
     EVOLUTION_API_URL=http://16.171.12.145:8080
     EVOLUTION_API_TOKEN=ee0695acb2cc6aa0c0f96556bca82317ac7fa8b85ed91de9c7849c0f1f579356
     ```
  2. Run `git pull origin main` on the Hostinger server to pull the changes.
  3. Restart the queue worker (`php artisan queue:restart`).

## The CSV / Data Import Request
The user noted: *"MD has approved the messages 2 automated messages per week for debtors after due. I have created the program to automate the process, all I need are the csv files containing students data like the one you gave me february."*

- **Status:** A template CSV (`student_import_template.csv`) has been generated and saved to the scratch directory for the user to fill out.
- **Next Agent Action:** Assist the user with creating an Artisan command or script to import the filled CSV data into the database.

## Summary of Next Steps for the New Agent:
1. **Verify WhatsApp Connection:** Confirm with the user if they successfully scanned the QR code via `http://16.171.12.145:8080/manager`.
2. **Deploy on Hostinger:** Verify `.env` is updated with the new IP/Token and code is pulled on Hostinger.
3. **Data Import:** Guide the user to upload and import their filled CSV template.
4. **Test:** Run `php artisan app:send-fee-reminders` and verify messages are delivered sequentially.
