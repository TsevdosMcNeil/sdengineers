//console.warn('HUBPDF SCRIPT LOADED');

document.addEventListener('DOMContentLoaded', () => {
  const wrappers = document.querySelectorAll('.hubpdf-wrapper');

  /**
   * Extract the most useful error message from the
   * WordPress REST response and HubSpot response.
   */
  function getHubSpotErrorMessage(data, fallbackStatus) {
    const hubspotBody = data?.hubspot?.body;

    if (
      typeof hubspotBody === 'string' &&
      hubspotBody.trim()
    ) {
      return hubspotBody;
    }

    if (hubspotBody?.message) {
      return hubspotBody.message;
    }

    if (
      Array.isArray(hubspotBody?.errors) &&
      hubspotBody.errors.length
    ) {
      return hubspotBody.errors
        .map((error) => {
          return (
            error.message ||
            error.errorType ||
            JSON.stringify(error)
          );
        })
        .join(' | ');
    }

    if (
      Array.isArray(hubspotBody?.validationResults) &&
      hubspotBody.validationResults.length
    ) {
      return hubspotBody.validationResults
        .map((error) => {
          return (
            error.message ||
            error.errorType ||
            JSON.stringify(error)
          );
        })
        .join(' | ');
    }

    return (
      data?.message ||
      data?.error ||
      `HubSpot rejected the submission. HTTP ${fallbackStatus}`
    );
  }

  /**
   * Add an error message inside the form.
   */
  function showFormError(form, message) {
    let formError = form.querySelector('.hubpdf-error');

    if (!formError) {
      formError = document.createElement('p');
      formError.className = 'hubpdf-error';
      form.prepend(formError);
    }

    formError.textContent = message;
    formError.style.display = 'block';
  }

  /**
   * Remove a previous form error.
   */
  function clearFormError(form) {
    const formError = form.querySelector(
      '.hubpdf-error'
    );

    if (formError) {
      formError.textContent = '';
      formError.style.display = 'none';
    }
  }

  wrappers.forEach((wrapper) => {
    const result = wrapper.querySelector('.hubpdf-result');
    const msg = result
      ? result.querySelector('.hubpdf-msg')
      : null;

    const download = result
      ? result.querySelector('.hubpdf-download')
      : null;

    const form = wrapper.querySelector('.hubpdf-form');
    const openBtn = wrapper.querySelector(
      '.hubpdf-openform'
    );

    const closeBtn = wrapper.querySelector(
      '.hubpdf-close'
    );

    const inner = wrapper.querySelector(
      '.hubpdf-inner-wrapper'
    );

    wrapper.addEventListener('click', () => {
      if (wrapper.classList.contains('is-open')) {
        return;
      }

      wrapper.classList.add('is-open');
    });

    if (inner) {
      inner.addEventListener('click', (event) => {
        event.stopPropagation();
      });
    }

    if (openBtn) {
      openBtn.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();

        wrapper.classList.toggle('is-open');
      });
    }

    if (closeBtn) {
      closeBtn.addEventListener('click', (event) => {
        event.preventDefault();

        wrapper.classList.remove('is-open');
      });
    }

    if (!form || !result || !msg) {
      console.error(
        'HUBPDF: Required form elements were not found.',
        {
          wrapper,
          form,
          result,
          msg
        }
      );

      return;
    }

    form.addEventListener('submit', async (event) => {
      event.preventDefault();

      //console.warn('HUBPDF SUBMIT HANDLER RUNNING');

      clearFormError(form);

      const firstnameInput = form.querySelector(
        'input[name="firstname"]'
      );

      const surnameInput = form.querySelector(
        'input[name="surname"]'
      );

      const companyInput = form.querySelector(
        'input[name="company"]'
      );

      const emailInput = form.querySelector(
        'input[name="email"]'
      );

      const marketingInput = form.querySelector(
        'input[name="marketing_opt_in"]'
      );

      const firstname = firstnameInput
        ? firstnameInput.value.trim()
        : '';

      const surname = surnameInput
        ? surnameInput.value.trim()
        : '';

      const company = companyInput
        ? companyInput.value.trim()
        : '';

      const email = emailInput
        ? emailInput.value.trim()
        : '';

      const marketingOptIn = marketingInput.value;

      /*
       * Use the browser's built-in required and email
       * validation before sending anything.
       */
      if (!form.reportValidity()) {
        return;
      }

      const missingFields = [];

      if (!firstname) {
        missingFields.push('First name');
      }

      if (!surname) {
        missingFields.push('Surname');
      }

      if (!email) {
        missingFields.push('Email');
      }

      if (missingFields.length) {
        const message =
          'Please complete the following required fields: ' +
          missingFields.join(', ');

        showFormError(form, message);

        return;
      }

      const emailPattern =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!emailPattern.test(email)) {
        showFormError(
          form,
          'Please enter a valid email address.'
        );

        if (emailInput) {
          emailInput.focus();
        }

        return;
      }

      if (
        typeof HUBPDF === 'undefined' ||
        !HUBPDF.restUrl
      ) {
        console.error(
          'HUBPDF configuration is missing.',
          typeof HUBPDF === 'undefined'
            ? null
            : HUBPDF
        );

        showFormError(
          form,
          'The form configuration is unavailable.'
        );

        return;
      }

      const payload = {
        firstname,
        surname,
        company,
        email,
        marketingOptIn,
        portalId: HUBPDF.portalId,
        formGuid: HUBPDF.formGuid,
        subscriptionTypeId: HUBPDF.subscriptionTypeId,
        consentText: HUBPDF.consentText,
        pageUri: window.location.href,
        pageName: document.title
      };

      form.style.opacity = '0';

      result.style.display = 'flex';
      msg.textContent = 'Sending...';

      if (closeBtn) {
        closeBtn.style.display = 'none';
      }

      if (download) {
        download.style.display = 'none';
      }

      // console.group('HubSpot form submission');
      // console.log('Request URL:', HUBPDF.restUrl);
      // console.log('Submitted payload:', payload);
      // console.groupEnd();

      try {
        const response = await fetch(HUBPDF.restUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': HUBPDF.nonce
          },
          body: JSON.stringify(payload)
        });

        const rawResponse = await response.text();

        let data;

        try {
          data = rawResponse
            ? JSON.parse(rawResponse)
            : {};
        } catch (parseError) {
          data = {
            message:
              rawResponse ||
              'The server returned an empty or invalid JSON response.'
          };

          console.error(
            'Unable to parse the REST response as JSON.',
            parseError
          );
        }

        // console.group('Complete form response');
        // console.log(
        //   'WordPress HTTP status:',
        //   response.status,
        //   response.statusText
        // );
        // console.log(
        //   'WordPress response headers:',
        //   Object.fromEntries(
        //     response.headers.entries()
        //   )
        // );
        // console.log('WordPress response body:', data);
        // console.log('Raw WordPress response:', rawResponse);

        // if (data?.hubspot) {
        //   console.log(
        //     'HubSpot status:',
        //     data.hubspot.status
        //   );

        //   console.log(
        //     'HubSpot status message:',
        //     data.hubspot.statusMessage
        //   );

        //   console.log(
        //     'HubSpot response body:',
        //     data.hubspot.body
        //   );

        //   console.log(
        //     'HubSpot response headers:',
        //     data.hubspot.headers
        //   );
        // }

        // if (data?.debug) {
        //   console.log(
        //     'HubSpot endpoint:',
        //     data.debug.endpoint
        //   );

        //   console.log(
        //     'HubSpot request payload:',
        //     data.debug.payload
        //   );
        // }

        // console.dir(data);
        // console.groupEnd();

        if (response.ok) {
          clearFormError(form);

          msg.textContent = 'Thanks!';

          if (download) {
            msg.textContent +=
              ' Your download is ready.';

            download.href = HUBPDF.pdfUrl;
            download.textContent = 'Download';
            download.style.display = 'inline-block';
          }

          if (closeBtn) {
            closeBtn.style.display =
              'inline-block';
          }

          return;
        }

        const errorMessage =
          getHubSpotErrorMessage(
            data,
            response.status
          );

        console.error(
          'HubSpot submission rejected:',
          {
            status: response.status,
            message: errorMessage,
            completeResponse: data
          }
        );

        /*
         * Restore the form so the visitor can correct
         * the submitted values.
         */
        form.style.opacity = '1';
        result.style.display = 'none';

        showFormError(
          form,
          `Error ${response.status}: ${errorMessage}`
        );

        if (download) {
          download.style.display = 'none';
        }

        if (
          errorMessage
            .toLowerCase()
            .includes('email') &&
          emailInput
        ) {
          emailInput.focus();
        } else if (
          errorMessage
            .toLowerCase()
            .includes('surname') &&
          surnameInput
        ) {
          surnameInput.focus();
        } else if (
          errorMessage
            .toLowerCase()
            .includes('first') &&
          firstnameInput
        ) {
          firstnameInput.focus();
        } else if (firstnameInput) {
          firstnameInput.focus();
        }
      } catch (error) {
        console.group(
          'HubSpot form network error'
        );
          console.error('Fetch failed:', error);
          console.error(
            'Request URL:',
            HUBPDF.restUrl
          );
          console.error(
            'Submitted payload:',
            payload
          );
        console.groupEnd();

        form.style.opacity = '1';
        result.style.display = 'none';

        showFormError(
          form,
          'Unable to contact the server. Please try again.'
        );
      }
    });
  });
});
