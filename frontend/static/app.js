document.addEventListener('DOMContentLoaded', () => {
    const generateBtn = document.getElementById('generate-btn');
    const copyBtn = document.getElementById('copy-btn');
    const emailAddressInput = document.getElementById('email-address');
    const inboxEmails = document.getElementById('inbox-emails');

    const modal = document.getElementById('email-modal');
    const closeModalBtn = document.querySelector('.close-btn');
    const modalSubject = document.getElementById('modal-subject');
    const modalFrom = document.getElementById('modal-from');
    const modalBody = document.getElementById('modal-body');

    let currentEmail = '';
    let emailCheckInterval;

    generateBtn.addEventListener('click', generateNewAddress);
    copyBtn.addEventListener('click', copyToClipboard);
    closeModalBtn.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });

    async function generateNewAddress() {
        try {
            const response = await fetch('/api/generate-address');
            const data = await response.json();
            if (data.address) {
                currentEmail = data.address;
                emailAddressInput.value = currentEmail;
                inboxEmails.innerHTML = '<p class="placeholder">Waiting for new emails...</p>';
                startEmailCheck();
            }
        } catch (error) {
            console.error('Error generating new address:', error);
            inboxEmails.innerHTML = '<p class="placeholder">Error generating address. Please try again.</p>';
        }
    }

    function copyToClipboard() {
        if (!emailAddressInput.value) return;
        emailAddressInput.select();
        document.execCommand('copy');
        // You could add a small visual notification that the text has been copied.
    }

    function startEmailCheck() {
        if (emailCheckInterval) {
            clearInterval(emailCheckInterval);
        }
        // Check for new emails every 10 seconds
        emailCheckInterval = setInterval(fetchEmails, 10000);
        fetchEmails(); // Also check immediately
    }

    async function fetchEmails() {
        if (!currentEmail) return;

        try {
            const response = await fetch(`/api/emails/${currentEmail}`);
            const emails = await response.json();
            displayEmails(emails);
        } catch (error) {
            console.error('Error fetching emails:', error);
        }
    }

    function displayEmails(emails) {
        if (emails.length === 0) {
            inboxEmails.innerHTML = '<p class="placeholder">Your inbox is empty.</p>';
            return;
        }

        inboxEmails.innerHTML = ''; // Clear current list
        emails.forEach(email => {
            const emailItem = document.createElement('div');
            emailItem.classList.add('email-item');

            const fromDiv = document.createElement('div');
            fromDiv.classList.add('email-from');
            fromDiv.textContent = email.from; // Use textContent to prevent XSS

            const subjectDiv = document.createElement('div');
            subjectDiv.classList.add('email-subject');
            subjectDiv.textContent = email.subject; // Use textContent to prevent XSS

            emailItem.appendChild(fromDiv);
            emailItem.appendChild(subjectDiv);

            emailItem.addEventListener('click', () => openEmailModal(email));
            inboxEmails.appendChild(emailItem);
        });
    }

    function openEmailModal(email) {
        modalSubject.textContent = email.subject;
        modalFrom.textContent = `From: ${email.from}`;
        modalBody.textContent = email.body; // Body is also set as text, which is safe.
        modal.style.display = 'block';
    }
});
