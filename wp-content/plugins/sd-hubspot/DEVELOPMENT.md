# Development

**Hubspot Form** 
  * *Form Name*: **PDF Gate**
  * *Edit Form*: Add fields and link them to `properties` (this is how Hubspot calls the fields of the contact forms: i.e. First Name , Company Name etc..)


---

## Adding a Marketing Opt-In Integration

HubSpot's current Forms documentation explicitly says that the Data Privacy field can contain individual communication checkboxes associated with a particular subscription type. When the visitor selects one, HubSpot subscribes the contact to that communication type

[OPT-IN-INTEGRATION.md](OPT-IN-INTEGRATION.md)

---

## Adding a new form field

When adding a new field to the *PDF Gate* form, the field needs to be configured both in **HubSpot** and in the **WordPress plugin**.

The data flow is:

`HTML form → JavaScript → WordPress REST API → HubSpot`

This means that adding the field to the HTML alone is not enough. The field must also be collected by JavaScript, passed to the WordPress REST endpoint, and included in the HubSpot API payload.

### Example: Email updates opt-in checkbox

The current example is the checkbox:

> Opt in to email updates from us

The HTML field is:

```html
<input
  type="checkbox"
  name="email_optin"
  class="hubpdf-optin-input"
/>
```

---

### 1. Create or add the field in HubSpot

Open the HubSpot form:

**PDF Gate**

Then:

1. Click **Edit Form**.
2. Add the required field.
3. Link the field to a HubSpot **Property**.

HubSpot refers to contact fields such as First Name, Last Name, Company Name, etc. as **Properties**.

For the opt-in checkbox, create or select the appropriate property for:

```text
Opt in to email updates from us
```

Take note of the property's **internal name**, as this is the name that needs to be used when submitting the field through the HubSpot API.

For example, if the HubSpot property's internal name is:

```text
email_optin
```

then the PHP HubSpot payload must also use:

```php
'name' => 'email_optin'
```

The display label and internal property name are not necessarily the same, so always check the property configuration in HubSpot.


#### If you need to create a property: 

In HubSpot:

1. Go to **Settings** (gear icon).
2. In the left sidebar, go to **Data Management → Properties**.
3. Make sure **Contact properties** is selected.
4. Click **Create property**.
5. Configure the property. For your checkbox, something like:

   * **Label:** `Opt in to email updates from us`
   * **Object type:** Contact
   * **Group:** Contact information (or another appropriate group)
   * **Field type:** Single checkbox
6. Click **Create**.

One important detail for your WordPress integration: after creating it, check the property's **internal name**. HubSpot will generate something along the lines of:

```text
opt_in_to_email_updates_from_us
```

The label is what humans see, but the **internal name is what you normally need to send through the HubSpot API**.

Then edit your **PDF Gate** form:

**Marketing → Forms → PDF Gate → Edit**

Add a field and select the new contact property you just created. This links the form field to the CRM property.

For your integration, the flow will essentially be:

```text
WordPress checkbox
        ↓
WordPress REST API / PHP
        ↓
HubSpot "PDF Gate" submission
        ↓
Contact property:
opt_in_to_email_updates_from_us
```

One thing worth considering before you create it: if this checkbox represents **marketing consent/newsletter subscription**, HubSpot has separate **communication subscriptions / legal consent** functionality. A custom boolean property records that the person ticked the box, but it isn't necessarily equivalent to subscribing the contact to a HubSpot marketing subscription type. If your goal is to actually opt them into marketing emails, we should make sure you're using the appropriate HubSpot mechanism rather than only storing `true/false`.


---

### 2. Add the field to the WordPress form

The HTML form is generated in:

```text
includes/class-plugin.php
```

Inside:

```php
public function shortcode_pdfgate()
```

For example:

```php
<label class="hubpdf-optin">
  <input
    type="checkbox"
    name="email_optin"
    class="hubpdf-optin-input"
  />

  <span class="hubpdf-optin-label">
    <?php echo esc_html($optin_label); ?>
  </span>
</label>
```

The field name is:

```text
email_optin
```

Keep this name consistent throughout the plugin.

---

### 3. Read the field in JavaScript

The JavaScript submission logic is located in:

```text
assets/hubpdf-gate.js
```

Inside the form submit handler, retrieve the checkbox:

```js
const emailOptinInput = form.querySelector(
  'input[name="email_optin"]'
);

const emailOptin = emailOptinInput
  ? emailOptinInput.checked
  : false;
```

Unlike a text input, a checkbox should normally be read using:

```js
.checked
```

rather than:

```js
.value
```

This gives a Boolean value:

```js
true
```

or:

```js
false
```

---

### 4. Add the field to the JavaScript payload

Add the value to the payload sent to the WordPress REST API:

```js
const payload = {
  firstname,
  surname,
  company,
  email,
  email_optin: emailOptin,
  portalId: HUBPDF.portalId,
  formGuid: HUBPDF.formGuid,
  pageUri: window.location.href,
  pageName: document.title
};
```

JavaScript then sends this payload to:

```text
/wp-json/hubpdf/v1/submit
```

using:

```js
fetch(HUBPDF.restUrl, {...})
```

---

### 5. Read the field in the WordPress REST API

The REST endpoint is defined in:

```text
includes/class-plugin.php
```

Inside:

```php
public function register_rest_routes()
```

Retrieve the value from the request:

```php
$email_optin = rest_sanitize_boolean(
  $request->get_param('email_optin')
);
```

At this stage, WordPress has received the value from JavaScript and can include it in the HubSpot submission.

---

### 6. Add the field to the HubSpot payload

The HubSpot API payload is created in:

```php
$payload = [
    'fields' => [
        // ...
    ],
];
```

Add the new property:

```php
[
    'name'  => 'email_optin',
    'value' => $email_optin,
],
```

For example:

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
            'value' => $surname,
        ],
        [
            'name'  => 'company',
            'value' => $company,
        ],
        [
            'name'  => 'email_optin',
            'value' => $email_optin,
        ],
    ],
    'context' => [
        'pageUri' => esc_url_raw(
            $request->get_param('pageUri')
        ),
        'pageName' => sanitize_text_field(
            $request->get_param('pageName')
        ),
    ],
];
```

The value used for `name` must match the **HubSpot property's internal name**.

---

### 7. Test the complete flow

After adding a new field, test both possible values where applicable.

For the opt-in checkbox:

* Submit the form with the checkbox selected.
* Confirm that the HubSpot contact/property is updated correctly.
* Submit another test with the checkbox unselected.
* Confirm that HubSpot receives the expected value.
* Check the browser Network tab for the WordPress REST request if the field is not being received correctly.
* Check the HubSpot API response if WordPress accepts the field but HubSpot rejects the submission.

The complete flow should be:

```text
WordPress form
    ↓
assets/hubpdf-gate.js
    ↓
WordPress REST API
    ↓
includes/class-plugin.php
    ↓
HubSpot Forms API
    ↓
HubSpot "PDF Gate" form/property
```

### Important

When adding future fields, keep the field/property name consistent across:

```text
HTML input name
JavaScript payload
WordPress REST request
HubSpot API payload
HubSpot property internal name
```

A mismatch between any of these names can cause the value to be missing or the HubSpot submission to be rejected.

---

## Why We Use `async / await` in `/assets/hubpdf-gate.js`

Because `fetch()` is **asynchronous**.

When you send a request to:

```js
fetch(HUBPDF.restUrl, { ... })
```

JavaScript does **not** wait for the server to respond.
It immediately continues executing the next line.

But in your case, you **must wait** for HubSpot to respond before:

* Showing the download link
* Displaying a success message
* Showing an error

So we use `async / await` to:

> Pause execution until the server responds — without blocking the browser UI.


### What `async` Does

```js
async function(e) { ... }
```

This tells JavaScript:

> “Inside this function, I will use `await`.”

Without `async`, you cannot use `await`.

### What `await` Does

```js
const response = await fetch(...)
```

This means:

> Wait here until the HTTP request finishes,
> then assign the result to `response`.

Without `await`, this would happen:

```js
const response = fetch(...);
```

And `response` would NOT be the server response.
It would be a **Promise object**, not usable yet.

### Why We Also Await `response.json()`

This is important:

```js
const data = await response.json();
```

Even after the request finishes, parsing JSON is also asynchronous.

So:

1. `await fetch(...)` → waits for server reply
2. `await response.json()` → waits for body to be parsed

### What Happens Without async/await?

You’d need Promise chains:

```js
fetch(...)
  .then(response => response.json())
  .then(data => {
      // success logic
  })
  .catch(error => {
      // error logic
  });
```

This works — but:

* Harder to read
* Harder to debug
* Becomes messy in more complex flows

`async/await` makes it look synchronous while remaining non-blocking.

### Important: It Does NOT Block the Page

Even though it looks like it's “waiting”, it does NOT freeze the browser.

The browser:

* Sends the request
* Keeps UI responsive
* Continues event loop
* Returns to your function when response arrives

So your form feels smooth.

### In Your Specific Flow

You need async/await because:

1. User clicks submit
2. You send to WordPress
3. WordPress sends to HubSpot
4. You must wait for confirmation
5. Only then reveal download button

If you didn’t wait:

* The download button might appear before HubSpot confirms
* You’d lose proper error handling

### When Would You NOT Need async/await?

If:

* You didn’t care about confirmation
* You didn’t need to read the response
* You just wanted to fire-and-forget

But for gated content → you must wait.

### What Happens If HubSpot Is Slow?

Because we're using `await`:

* The message shows “Sending…”
* The button stays hidden
* The UI remains responsive
* When response arrives → we update UI accordingly

That’s correct behavior.

---

## Why do we need to call 'settings_fields( 'sdhbt-settings-group' );' in the form?


> `settings_fields()` adds hidden security + routing fields that allow `options.php` to process and save your settings safely.

Without it, WordPress doesn’t know:

* Which settings group you're saving
* Whether the request is legitimate (nonce)
* Whether you're allowed to update those options


### What `settings_fields()` Actually Does

When you call:

```php
settings_fields('sdhbt-settings-group');
```

WordPress outputs hidden inputs like:

```html
<input type="hidden" name="option_page" value="sdhbt-settings-group">
<input type="hidden" name="action" value="update">
<input type="hidden" name="_wpnonce" value="abc123xyz">
```

These are critical.


#### `option_page`

```html
<option_page = sdhbt-settings-group>
```

This tells `options.php`:

> “The settings group being saved is `sdhbt-settings-group`.”

WordPress then checks:

```php
register_setting('sdhbt-settings-group', ...)
```

If that group is not registered → you get your error:

> “options page is not in the allowed options list.”

So `settings_fields()` connects the form to `register_setting()`.


#### `_wpnonce`

This is a security token.

It protects against:

* CSRF attacks
* Malicious external form submissions

Without it, WordPress will reject the request.

#### `action=update`

This tells `options.php` what operation to perform.

### What Happens If You Remove `settings_fields()`?

Then your form submits to:

```php
action="options.php"
```

But WordPress receives:

* No option_page
* No nonce
* No allowed group

So it blocks the save.

### Conceptually

Think of it like this:

| Function             | Purpose                                                   |
| -------------------- | --------------------------------------------------------- |
| `register_setting()` | Tells WP which options are allowed to be saved            |
| `settings_fields()`  | Tells WP which group this form belongs to + adds security |
| `options.php`        | Processes the save                                        |

All three must match.

### Could You Avoid It?

Yes — but then you must:

* Create your own form handler
* Add your own nonce
* Use `update_option()` manually
* Handle capability checks yourself

Example:

```php
if (isset($_POST['my_option'])) {
    update_option('my_option', sanitize_text_field($_POST['my_option']));
}
```

But then you’re bypassing the Settings API.

For admin settings pages, using:

```php
register_setting() + settings_fields()
```

is the correct WordPress-native way.

### In The Plugin Specifically

You need:

```php
register_setting('sdhbt-settings-group', 'hubspot_form_guid');
register_setting('sdhbt-settings-group', 'hubspot_portal_id');
```

AND

```php
settings_fields('sdhbt-settings-group');
```

Otherwise `options.php` won’t allow saving.

