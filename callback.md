Handle Response
Call your backend endpoint, then redirect to the payment URL it returns. After checkout, TGIPAY redirects back with callback params such as ref, status, and tgipay=1. Use ref to verify final status from the status endpoint.

// 1) Ask your backend to create the payment session
const initiateResponse = await fetch('/api/payments/initiate', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    customerFirstName: 'John',
    customerLastName: 'Doe',
    customerEmail: 'john@example.com',
    amount: 50000
  })
});

const initiateResult = await initiateResponse.json();

if (!initiateResult.status || !initiateResult.checkoutUrl) {
  throw new Error(initiateResult.message || 'Payment initiation failed');
}

// 2) Redirect customer to TGIPAY checkout
window.location.href = initiateResult.checkoutUrl;

// 3) On callback page, verify with transaction reference
const params = new URLSearchParams(window.location.search);
const ref = params.get('ref');

if (ref) {
  const statusResponse = await fetch(
    `https://integration-service.tgipay.com/integration/api/v1/payment/status/${encodeURIComponent(ref)}`,
    {
      headers: {
        'integration-key': 'YOUR_PUBLIC_KEY'
      }
    }
  );

  const statusResult = await statusResponse.json();

  if (statusResult.status && statusResult.data) {
    const finalStatus = statusResult.data.status; // SUCCESSFUL | FAILED | PENDING | CANCELLED
    updateOrderStatus(ref, finalStatus);
  }
}