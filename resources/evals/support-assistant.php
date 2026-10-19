<?php

/*
 * Labeled tickets for `php artisan assistant:eval`. Each sample names the
 * seeded tenant it belongs to; the command runs it as that tenant.
 * Written fresh, not copied from the seeded tickets.
 */

return [
    ['tenant' => 'acme', 'subject' => 'Refund for last month', 'body' => 'We cancelled a project on the 3rd but were billed for the full month. Can we get the difference back?', 'category' => 'billing'],
    ['tenant' => 'acme', 'subject' => 'Card expired', 'body' => 'Our company card expired and I need to put a new one on file before the next invoice.', 'category' => 'billing'],
    ['tenant' => 'acme', 'subject' => 'Lost my authenticator', 'body' => 'I reset my phone and the authenticator app is gone. I still have my recovery codes somewhere, how do I use them?', 'category' => 'account'],
    ['tenant' => 'acme', 'subject' => 'Invite link expired', 'body' => 'A new hire says the invitation I sent last week no longer works. How do I get them in?', 'category' => 'account'],
    ['tenant' => 'acme', 'subject' => 'Invoice PDF is blank', 'body' => 'When I download the September invoice the PDF opens but every page is empty. Other months are fine.', 'category' => 'bug'],
    ['tenant' => 'acme', 'subject' => 'Reports page crashes', 'body' => 'The reports page shows "Something went wrong" as soon as I pick a date range longer than 90 days.', 'category' => 'bug'],
    ['tenant' => 'acme', 'subject' => 'SSO with Okta', 'body' => 'Is single sign-on on the roadmap? We would love to log in through Okta instead of passwords.', 'category' => 'feature-request'],
    ['tenant' => 'acme', 'subject' => 'Scheduled exports', 'body' => 'It would save us a lot of time if the CSV export could run automatically every Monday and email us the file.', 'category' => 'feature-request'],
    ['tenant' => 'globex', 'subject' => 'Payment keeps failing', 'body' => 'Our bank says nothing is blocked, but every renewal attempt this week has been declined.', 'category' => 'billing'],
    ['tenant' => 'globex', 'subject' => 'Two promo codes', 'body' => 'We have a partner code and a seasonal code. Can we stack them on the next invoice?', 'category' => 'billing'],
    ['tenant' => 'globex', 'subject' => 'Agent needs billing access', 'body' => 'One of our agents needs to see invoices. What role do they need and who can grant it?', 'category' => 'account'],
    ['tenant' => 'globex', 'subject' => 'Update login email', 'body' => 'Our company changed domains. How do I switch my login to the new email address?', 'category' => 'account'],
    ['tenant' => 'globex', 'subject' => 'Order totals wrong', 'body' => 'The order list shows totals without tax, but opening each order shows the correct amount with tax.', 'category' => 'bug'],
    ['tenant' => 'globex', 'subject' => 'Images not uploading', 'body' => 'Product images over 2 MB get stuck at 99% and never finish uploading, on every browser we tried.', 'category' => 'bug'],
    ['tenant' => 'globex', 'subject' => 'Multi-currency pricing', 'body' => 'We are expanding to Europe. Could you add the option to set product prices in euros as well as dollars?', 'category' => 'feature-request'],
];
