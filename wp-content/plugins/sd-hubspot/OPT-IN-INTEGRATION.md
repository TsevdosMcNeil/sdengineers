# HubSpot Marketing Opt-In Integration

This section explains how to add the **“Opt in to email updates from us”** checkbox to the PDF Gate form and send the consent correctly to HubSpot.

## Overview

The newsletter opt-in should **not** be created as a normal HubSpot Contact property.

HubSpot manages marketing email consent through **Communication Subscriptions**. In this case, the relevant subscription type is:

```text
Marketing Information
```

The WordPress form should therefore send the consent through HubSpot's `legalConsentOptions` object when submitting the form via the HubSpot Forms API.

The flow is:

```text
WordPress checkbox
        ↓
JavaScript form submission
        ↓
WordPress REST API / PHP
        ↓
HubSpot Forms API
        ↓
legalConsentOptions
        ↓
Marketing Information subscription
```

---

## 1. Update the HubSpot form

Open HubSpot and go to:

```text
Marketing → Forms → PDF Gate → Edit
```

Add a **Data Privacy** field to the form.

Depending on the HubSpot interface, this can normally be found under:

```text
Add → Other → Data Privacy
```

Configure the field to use the existing communication subscription type:

```text
Marketing Information
```

Set the checkbox text to:

```text
Opt in to email updates from us
```

The checkbox should remain optional so users can still download the PDF without subscribing to marketing emails.

---

## 2. Add the checkbox to the WordPress form

Add the checkbox to the form HTML if it is not already present.

For example:

```html
<label>
    <input
        type="checkbox"
        name="marketing_opt_in"
        value="1"
    >
    Opt in to email updates from us
</label>
```

The field name used in WordPress does **not** need to match a HubSpot Contact property because the value will be converted into HubSpot consent data before submission.

---

## 3. Read the checkbox value in JavaScript

When the form is submitted, check whether the checkbox has been selected.

For example:

```js
const marketingOptIn = form.querySelector(
    '[name="marketing_opt_in"]'
)?.checked ?? false;
```

Include the value in the data sent to the WordPress REST endpoint:

```js
const data = {
    email,
    firstname,
    lastname,
    company,
    marketing_opt_in: marketingOptIn,
};
```

---

## 4. Validate the value in the WordPress REST API

The REST API should treat the checkbox as a boolean value.

For example:

```php
$marketing_opt_in = ! empty( $request['marketing_opt_in'] );
```

Do not send `marketing_opt_in` to HubSpot as a normal form field.

For example, **do not** add this:

```php
[
    'name'  => 'marketing_opt_in',
    'value' => 'true',
]
```

There is no custom HubSpot Contact property required for this value.

---

## 5. Add `legalConsentOptions` to the HubSpot payload

The existing HubSpot submission will probably contain something similar to:

```php
$payload = [
    'fields' => [
        [
            'name'  => 'email',
            'value' => $email,
        ],
        [
            'name'  => 'firstname',
            'value' => $firstname,
        ],
        [
            'name'  => 'lastname',
            'value' => $lastname,
        ],
        [
            'name'  => 'company',
            'value' => $company,
        ],
    ],
];
```

When the user selects the marketing checkbox, add the consent information using:

```text
legalConsentOptions
```

The consent should be associated with the HubSpot:

```text
Marketing Information
```

subscription type.

The final payload structure should conceptually look like:

```json
{
  "fields": [
    {
      "name": "email",
      "value": "example@example.com"
    },
    {
      "name": "firstname",
      "value": "John"
    },
    {
      "name": "lastname",
      "value": "Smith"
    },
    {
      "name": "company",
      "value": "Example Ltd"
    }
  ],
  "legalConsentOptions": {
    "consent": {
      "consentToProcess": true,
      "text": "Consent text",
      "communications": [
        {
          "value": true,
          "subscriptionTypeId": 123,
          "text": "Opt in to email updates from us"
        }
      ]
    }
  }
}
```

> **Important:** `subscriptionTypeId` must be the ID of the **Marketing Information** subscription type in the HubSpot portal. Do not hard-code an ID copied from another HubSpot account.

---

## 6. Finding the HubSpot Subscription Type ID

The marketing opt-in is connected to the HubSpot **Marketing Information** subscription type.

The `subscriptionTypeId` is required when sending consent through the HubSpot Forms API using `legalConsentOptions`.

### Current Subscription Type ID

For the **Marketing Information** subscription type:

```text
332284189
```

This is the value that should be used in the HubSpot payload:

```json
{
  "value": true,
  "subscriptionTypeId": 332284189,
  "text": "I agree to receive other communications from SD Engineers."
}
```

### How the ID was found

HubSpot does not display the subscription type ID directly under:

```text
Settings → Marketing → Email → Subscription Types
```

However, the ID can be found by adding the relevant **Data Privacy / Communication Consent** field to the HubSpot form and previewing the generated form.

Inspect the consent checkbox using the browser's Developer Tools.

HubSpot generates an input similar to:

```html
<input
    type="checkbox"
    name="LEGAL_CONSENT.subscription_type_332284189"
    value="false"
>
```

The number following:

```text
LEGAL_CONSENT.subscription_type_
```

is the HubSpot subscription type ID.

Therefore:

```text
LEGAL_CONSENT.subscription_type_332284189
                                └─────────┘
                           Subscription ID
```

In this case:

```text
subscriptionTypeId = 332284189
```

### Important

Do not use the HubSpot **Portal ID**, **Form GUID**, or the UUIDs generated for individual form elements as the subscription type ID.

The relevant ID is specifically the numeric value associated with:

```text
LEGAL_CONSENT.subscription_type_{ID}
```

If the HubSpot subscription configuration is changed in the future, preview the HubSpot form again and inspect the consent checkbox to confirm that the correct `subscriptionTypeId` is still being used.

---

## 7. Only send marketing consent when selected

The newsletter checkbox is optional.

If the user selects:

```text
Opt in to email updates from us
```

send the corresponding marketing communication consent to HubSpot.

If the checkbox is **not selected**, do not subscribe the user to Marketing Information.

An unchecked checkbox should also **not be interpreted as an unsubscribe request**.

The form submission should still continue normally so the user can access the requested PDF.

---

## 8. Test the integration

Test both possible scenarios.

### Test 1 — Opt-in selected

Submit the PDF Gate form with:

```text
Opt in to email updates from us: checked
```

Then open the Contact in HubSpot and check:

```text
Communication subscriptions
```

The expected result is:

```text
Marketing Information → Subscribed
```

### Test 2 — Opt-in not selected

Submit another test contact with:

```text
Opt in to email updates from us: unchecked
```

The form and PDF download should still work normally.

The contact should **not** be subscribed to:

```text
Marketing Information
```

---

## 9. Important distinction

Do not confuse the following HubSpot property:

```text
Opted out of email: Marketing Information
```

with an opt-in checkbox.

This is a HubSpot system property related to unsubscribe status. It should not be manually updated to implement the newsletter opt-in.

The subscription should instead be managed through HubSpot's **Communication Subscriptions / legal consent** system.

---

## Implementation summary

The required changes are:

1. Add a **Data Privacy / Marketing Information** consent field to the HubSpot `PDF Gate` form.
2. Keep the checkbox in the WordPress form.
3. Read its checked state in JavaScript.
4. Pass the boolean value to the WordPress REST endpoint.
5. Do **not** create or update a custom HubSpot Contact property.
6. When selected, send the consent through HubSpot's `legalConsentOptions`.
7. Associate the consent with the `Marketing Information` subscription type.
8. Test both opted-in and non-opted-in submissions in HubSpot.
