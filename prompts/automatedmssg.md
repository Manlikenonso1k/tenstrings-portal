I have a Laravel backend using Filament. I want to use the Evolution API (an unofficial WhatsApp API) to send automated fee reminders to students and registration alerts to staff. 

I need you to act as an expert DevOps and Laravel engineer. Please provide a complete guide divided into two parts: installation/setup, and the Laravel implementation.

---

### PART 1: Evolution API Installation & Setup
Please provide the fastest and most secure method to get the Evolution API up and running on a Linux server. 
1. Provide the `docker-compose.yml` file configuration for the Evolution API.
2. Include the essential environment variables (`ENV`) needed to secure the API (like setting up the global API Token, enabling the Webhook features, and setting up the data store).
3. Briefly explain how to access the dashboard/instance manager to scan the WhatsApp QR code using a new WhatsApp Business SIM card.

---

### PART 2: Safe Laravel Automation (Anti-Ban Architecture)
I need you to write the backend logic to send these messages without getting my WhatsApp number banned by Meta's anti-spam filters. 

Please create a Laravel Queued Job and the dispatch logic matching these strict constraints:
1. No raw foreach loops with synchronous API calls.
2. Implement human-like throttling: Do not use `sleep()` inside the job itself, as that blocks the queue worker. Instead, when dispatching the jobs from the scheduler or controller, apply a staggered, cumulative delay using Laravel's `->delay()` method. 
3. The delay must be randomized between 5 to 10 seconds per message. For example:
   - Message 1 sends immediately (delay = 0s)
   - Message 2 sends after a random delay of 7s (delay = 7s)
   - Message 3 sends after another random 8s (total delay = 15s)
   - Message 4 sends after another random 6s (total delay = 21s), etc.
4. Include a robust try-catch block inside the Job so that if the Evolution API endpoint is down, timed out, or returns a bad status code, it logs the failure gracefully but does not crash the entire queue system.
5. Use Laravel's standard `Http::withToken()->post()` facade to talk to the Evolution API `/message/sendText` endpoint.

Please provide clean, copy-pasteable code for:
- The Queued Job class (`SendWhatsAppMessage`).
- The dispatch logic inside a Custom Artisan Console Command (`app:send-fee-reminders`) that queries students owing money and queues their messages with the cumulative random delays.
- A quick example of how to dispatch this same job instantly for a staff alert when a new student is created via Filament.