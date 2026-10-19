
(() => {
    'use strict';

    function initialize() {
        const form = document.getElementById('tobCheckoutForm');

        if (!form || form.dataset.addressesInitialized === '1') {
            return;
        }

        form.dataset.addressesInitialized = '1';

        const radioSelector =
            'input[name="saved_address_id"]:checked';

        const fields = [
            'name',
            'phone',
            'address',
            'landmark',
            'city',
            'state',
            'pincode'
        ];

        const saveOptions =
            document.getElementById('tobSaveAddressOptions');

        const saveCheckbox =
            document.getElementById('tobSaveAddress');

        const labelWrap =
            document.getElementById('tobAddressLabelWrap');

        const labelInput =
            document.getElementById('tobAddressLabel');

        const userName =
            form.querySelector('[name="name"]')?.value || '';

        const userPhone =
            form.querySelector('[name="phone"]')?.value || '';

        function setField(name, value) {
            const el = form.querySelector(`[name="${name}"]`);

            if (!el) return;

            el.value = value == null ? '' : String(value);

            el.dispatchEvent(
                new Event('input', { bubbles: true })
            );

            el.dispatchEvent(
                new Event('change', { bubbles: true })
            );
        }

        function updateSaveUI() {
            const selected =
                form.querySelector(radioSelector);

            const isNew =
                !selected || selected.value === 'new';

            if (saveOptions) {
                saveOptions.hidden = !isNew;
            }

            if (labelWrap) {
                labelWrap.hidden =
                    !isNew || !saveCheckbox?.checked;
            }

            if (saveCheckbox && !isNew) {
                saveCheckbox.checked = false;
            }

            if (labelInput) {
                labelInput.required =
                    isNew && !!saveCheckbox?.checked;
            }
        }

        function applySelectedAddress() {
            const selected =
                form.querySelector(radioSelector);

            if (selected && selected.value !== 'new') {
                try {
                    const data = JSON.parse(
                        selected.dataset.savedAddress || '{}'
                    );

                    for (const field of fields) {
                        setField(field, data[field]);
                    }
                } catch (error) {
                    console.error(
                        'Unable to read saved address data',
                        error
                    );
                }
            } else if (selected && selected.value === 'new') {
                for (const field of fields) {
                    setField(field, '');
                }

                setField('name', userName);
                setField('phone', userPhone);
            }

            updateSaveUI();
        }

        form.querySelectorAll(
            'input[name="saved_address_id"]'
        ).forEach(radio => {
            radio.addEventListener(
                'change',
                applySelectedAddress
            );
        });

        saveCheckbox?.addEventListener(
            'change',
            updateSaveUI
        );

        // Fill the default saved address on page load.
        applySelectedAddress();

        // Restore autofill after browser back navigation.
        window.addEventListener('pageshow', event => {
            if (event.persisted) {
                applySelectedAddress();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener(
            'DOMContentLoaded',
            initialize
        );
    } else {
        initialize();
    }
})();
