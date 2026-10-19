<?php

/*
 * Sample tickets for `php artisan support:eval`, each with the category we expect.
 * New samples (not the seeded tickets); the last one in each group is borderline.
 */

return [
    ['subject' => 'Billed for the wrong plan', 'body' => 'We are on Starter but this month\'s invoice charged us the Pro price.', 'expected' => 'billing'],
    ['subject' => 'Update billing email', 'body' => 'Please send future invoices to finance@ourcompany.com instead of my address.', 'expected' => 'billing'],
    ['subject' => 'Refund for unused months', 'body' => 'We prepaid for a year but are shutting down. Can we get the remaining months refunded?', 'expected' => 'billing'],
    ['subject' => 'Card expired, account still active?', 'body' => 'My card on file expired. Will we lose access, and where do I add the new one?', 'expected' => 'billing'],

    ['subject' => 'Make a teammate an admin', 'body' => 'How do I give my colleague admin rights so she can invite people?', 'expected' => 'account'],
    ['subject' => 'Delete my personal data', 'body' => 'I am leaving the team. Please remove my profile and personal information.', 'expected' => 'account'],
    ['subject' => 'Locked out after too many attempts', 'body' => 'I typed the wrong password a few times and now it says my account is locked.', 'expected' => 'account'],

    ['subject' => 'Magic link not working', 'body' => 'Since yesterday\'s release, the sign-in link in our emails shows "Invalid token" for everyone on our team, even when it was sent seconds ago.', 'expected' => 'bug'],
    ['subject' => 'Search crashes on emoji', 'body' => 'Typing an emoji into the search box makes the whole page go blank.', 'expected' => 'bug'],
    ['subject' => 'Saved filters disappear', 'body' => 'Filters I save on the tickets list are gone the next time I log in.', 'expected' => 'bug'],
    ['subject' => 'Charts empty in Safari', 'body' => 'The usage charts render fine in Chrome but are empty in Safari 18.', 'expected' => 'bug'],
    ['subject' => 'Receipt email has a broken link', 'body' => 'The "View receipt" button in the payment email goes to a 404 page.', 'expected' => 'bug'],

    ['subject' => 'Custom fields on tickets', 'body' => 'We would love to add our own fields, like order number, to each ticket.', 'expected' => 'feature-request'],
    ['subject' => 'Microsoft Teams notifications', 'body' => 'Could you support posting new ticket alerts to a Teams channel?', 'expected' => 'feature-request'],
    ['subject' => 'Invoices in euros', 'body' => 'Would you consider letting us pay and receive invoices in EUR instead of USD?', 'expected' => 'feature-request'],
];
