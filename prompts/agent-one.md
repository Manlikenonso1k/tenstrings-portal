You are a senior Laravel developer doing a knowledge extraction task.
Your job is to READ and STUDY this school project codebase, then write
a PAYMENT_KNOWLEDGE.md file that a separate agent can use to build a
completely different project (a beach event ticketing system).

DO NOT build anything. DO NOT modify any files. READ ONLY.

---

## STEP 1 — Find and read these files (search the codebase for them)

Look for files related to:
- TGI Titan payment (initiation, redirect, webhook/callback handler)
- Paystack payment (initiation, verification)
- The controller(s) that handle payment logic
- routes/web.php and routes/api.php — find every payment-related route
- config/services.php — find Titan and Paystack config keys
- The migration or model for whatever table stores payment/order records
- Any TGI documentation file (.md, .txt, .pdf) inside the codebase — read it fully
- The .env.example file — extract only payment-related keys (no real secrets)
- The success page / redirect after payment is confirmed
- The PDF or document that gets generated after payment succeeds

For each file you find, read it completely before moving on.

---

## STEP 2 — Understand and document exactly how each gateway works

For TGI Titan, answer these from the actual code:
- What fields are sent in the initiation POST request?
- How is the signature/HMAC built — what is hashed, with what key, in what order?
- What URL is the customer redirected to?
- What does the webhook callback receive — what fields, what headers?
- How is the webhook signature verified?
- What DB field is updated when payment is confirmed?

For Paystack, answer these from the actual code:
- Is it inline JS or a redirect?
- What fields does the frontend send to the backend?
- How does the backend verify — what endpoint, what header/token?
- What DB field is updated when payment is confirmed?

---

## STEP 3 — Write the PAYMENT_KNOWLEDGE.md file

Write a single clean markdown file with these sections:

1. **How TGI Titan Works Here** — exact flow from the actual code, with
   the real method names, field names, and signature logic copied faithfully.
   Include a working code snippet for: initiation, redirect, webhook handler.

2. **How Paystack Works Here** — same detail level. Include initiation
   and verification code snippets.

3. **The Database** — the exact table/column names used for storing
   payment records, what each column means, and which columns change
   on payment success.

4. **What Happens After Payment** — describe the success redirect and
   the PDF/document generation: what triggers it, what library is used,
   what data goes into it.

5. **Routes** — list every payment-related route with its method (GET/POST),
   URI, and which controller method it calls.

6. **Config Keys** — list every .env key needed for both gateways with
   DUMMY placeholder values (e.g. TITAN_SECRET=your_titan_secret_here).
   Do NOT use any real credentials from the project.

7. **Lessons and Gotchas** — anything in the code that is non-obvious:
   edge cases handled, middleware exceptions (e.g. CSRF exclusions),
   retry logic, or anything a new agent would likely get wrong.

---

## STEP 4 — Output rules

- Write ONLY the markdown file content, nothing else
- Every code snippet must be copied from the ACTUAL code you read,
  not invented or paraphrased
- If you cannot find a file, say so explicitly inside the relevant
  section with: ⚠️ NOT FOUND — searched for [filename] but could not locate
- Use dummy values for all credentials
- Do not summarise the whole school project — focus ONLY on payment logic