# Evolution API — AWS Agent Setup Prompt

> **Copy and paste this entire prompt to your AWS Copilot / Agent.**

---

## Context

I have a **Laravel + Filament** application hosted on **Hostinger** (the Tenstrings student portal). It needs to send WhatsApp messages via the **Evolution API** running in Docker on this AWS server.

My Laravel app makes HTTP calls to the Evolution API using these two environment variables:

```
EVOLUTION_API_URL=<base URL of the Evolution API, e.g. http://<server-ip>:8080>
EVOLUTION_API_TOKEN=<the global API key / token set in the Evolution API>
```

The Laravel code calls endpoints like:
```
POST {EVOLUTION_API_URL}/message/sendText/tenstrings-alerts
Authorization: Bearer {EVOLUTION_API_TOKEN}
```

---

## What I Need You To Do

### 1. Provide the API credentials

Please find and return the following values from the Evolution API Docker container's environment / config:

| Variable | What it is |
|---|---|
| `EVOLUTION_API_URL` | The full base URL (protocol + host/IP + port) that the Evolution API is accessible on externally. If it's behind a reverse proxy (Nginx/Caddy), give me the public HTTPS URL. If it's direct Docker, give me `http://<public-ip>:8080`. |
| `EVOLUTION_API_TOKEN` | The global API key. This is usually set as the `AUTHENTICATION_API_KEY` environment variable in the `docker-compose.yml` or `.env` file for the Evolution API container. |

**How to find them:**
```bash
# Check the running container's environment
docker inspect <evolution-container-name> --format '{{range .Config.Env}}{{println .}}{{end}}' | grep -E 'AUTHENTICATION_API_KEY|SERVER_URL'

# Or check the docker-compose file
cat docker-compose.yml | grep -A5 -i 'authentication\|server_url\|api_key'
```

### 2. Confirm the instance name

My Laravel code sends messages through an instance called **`tenstrings-alerts`**. Please confirm:
- Does this instance exist?
- Is it connected (status = `open`)?
- If not, what instances are available and what are their statuses?

```bash
# List instances via API
curl -s -H "apikey: <AUTHENTICATION_API_KEY>" http://localhost:8080/instance/fetchInstances | python3 -m json.tool
```

### 3. Confirm networking / firewall

My Hostinger server needs to reach this Evolution API over the internet. Please check:

- Is port **8080** (or whatever port the API runs on) open in the **AWS Security Group** inbound rules?
- If there's a reverse proxy (Nginx/Caddy) in front, is it configured and working?
- Can the API be reached from outside? Test with:

```bash
curl -s http://<public-ip>:8080/
```

> **If port 8080 is NOT open and you don't want to expose it directly**, set up a reverse proxy with HTTPS (e.g., Nginx + Let's Encrypt on a subdomain like `whatsapp-api.tenstrings.com`). Then give me that HTTPS URL as my `EVOLUTION_API_URL`.

### 4. QR Code Scanning — How do I connect my WhatsApp number?

I need to scan a QR code to link my WhatsApp Business number to the `tenstrings-alerts` instance. Please:

1. **Tell me the exact URL** I need to visit in my browser to access the Evolution API Manager UI. It should be one of:
   - `http://<public-ip>:8080/manager` (if port is open)
   - `https://<your-domain>:8080/manager` (if behind reverse proxy)

2. **Make sure the Manager UI is enabled** in the Docker environment:
   ```
   MANAGER_ENABLED=true
   ```

3. **Walk me through the QR scan flow:**
   - Do I log into the Manager UI with the API key?
   - Which instance do I select (`tenstrings-alerts`)?
   - Where exactly is the "Connect" or "QR Code" button?

4. **If the Manager UI is NOT enabled or accessible**, generate a QR code for me via the API:
   ```bash
   curl -s -X POST \
     -H "apikey: <AUTHENTICATION_API_KEY>" \
     -H "Content-Type: application/json" \
     -d '{"instanceName": "tenstrings-alerts", "qrcode": true}' \
     http://localhost:8080/instance/connect/tenstrings-alerts
   ```
   Then return the QR code image or base64 data so I can scan it with my phone.

---

## Summary — Please Return These Values

Once you've checked everything, respond with a filled-in block like this:

```env
# Evolution API (WhatsApp) — paste into Hostinger .env
EVOLUTION_API_URL=https://xxxxxxxx
EVOLUTION_API_TOKEN=xxxxxxxx
EVOLUTION_INSTANCE_NAME=tenstrings-alerts
```

And confirm:
- [ ] Instance `tenstrings-alerts` exists and is connected
- [ ] Port / URL is accessible from the internet
- [ ] Manager UI URL for QR scanning: `http://xxxxx/manager`
- [ ] QR code has been scanned and WhatsApp is linked

---

## ⚠️ Important Notes

- **Do NOT change** any Evolution API settings without telling me first.
- If `DATABASE_SAVE_DATA_CONTACTS` is currently `false`, leave it for now — I'll decide later if I want contacts persisted.
- If the instance `tenstrings-alerts` doesn't exist, **create it** with the API:
  ```bash
  curl -s -X POST \
    -H "apikey: <AUTHENTICATION_API_KEY>" \
    -H "Content-Type: application/json" \
    -d '{"instanceName": "tenstrings-alerts", "qrcode": true}' \
    http://localhost:8080/instance/create
  ```

  aws-evolution-setup.md

EVOLUTION_API_URL=http://16.171.12.145:8080
EVOLUTION_API_TOKEN=ee0695acb2cc6aa0c0f96556bca82317ac7fa8b85ed91de9c7849c0f1f579356
EVOLUTION_INSTANCE_NAME=tenstrings-alerts

delet diploma in gosple music performance
Delete the music business course
delete song writing course