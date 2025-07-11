# CSRF Token Mismatch Fix for Digital Ocean

This document provides instructions for fixing the CSRF token mismatch error that occurs during user registration on the Digital Ocean App Platform.

## Changes Made

1. Updated the Vue.js registration form to fetch the CSRF token before submitting the form
2. Added `withCredentials: true` to axios requests to ensure cookies are sent
3. Updated the axios configuration in bootstrap.js to include credentials by default
4. Temporarily excluded the `/api/register` endpoint from CSRF verification (for debugging)
5. Updated the TrustProxies middleware to trust all proxies

## Deployment Instructions

After pushing these changes to your repository, follow these steps:

1. **Update Environment Variables**

   Ensure the following environment variables are set in the Digital Ocean App Platform dashboard:

   ```
   SESSION_DRIVER=database
   SESSION_LIFETIME=120
   SESSION_SECURE_COOKIE=true
   SESSION_DOMAIN=tostomain-achxn.ondigitalocean.app
   SESSION_SAME_SITE=lax
   TRUSTED_PROXIES=*
   SANCTUM_STATEFUL_DOMAINS=tostomain-achxn.ondigitalocean.app
   ```

2. **Clear Cache**

   After deploying, run these commands:

   ```
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

3. **Verify Sessions Table**

   Ensure the sessions table exists in your database:

   ```
   php artisan migrate
   ```

4. **Check Logs**

   Monitor the logs for any CSRF-related errors:

   ```
   php artisan log:tail
   ```

## Additional Troubleshooting

If the issue persists:

1. **Check Browser Console**

   Open the browser developer tools and check for any CSRF-related errors in the console.

2. **Inspect Network Requests**

   In the browser developer tools, inspect the network requests to see if the CSRF token is being sent correctly.

3. **Temporarily Disable CSRF Protection**

   If needed, you can temporarily add more routes to the `$except` array in `app/Http/Middleware/VerifyCsrfToken.php`.

4. **Cookie Settings**

   Ensure cookies are being set correctly by checking the browser's storage inspector.

5. **Remove CSRF Exception**

   Once the issue is resolved, remove the temporary exception from the VerifyCsrfToken middleware.

## Long-term Solution

The temporary exclusion of the `/api/register` endpoint from CSRF verification is not recommended for production. Once you confirm that the other fixes are working, remove this exception and ensure proper CSRF token handling. 