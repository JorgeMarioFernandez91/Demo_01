<?php

/*
 * Sample tickets for `php artisan support:eval`, each with the category we expect.
 * Written fresh rather than copied from the seeder, and a few are deliberately
 * borderline (marked below) so the score reflects real-world ambiguity.
 */

return [
    // billing
    ['subject' => 'Invoice shows our old company name', 'body' => 'We rebranded last month but our invoices still list the old company name. Can future invoices use the new one?', 'expected' => 'billing'],
    ['subject' => 'Credit for removed seats', 'body' => 'We removed five seats halfway through the month. Do we get credit for the unused time?', 'expected' => 'billing'],
    ['subject' => 'Charged after cancelling', 'body' => 'I cancelled our subscription last week, but my card was charged again this morning.', 'expected' => 'billing'],
    // borderline: billing vs account
    ['subject' => 'Payment failed, will we be suspended?', 'body' => 'Our card payment failed yesterday. Will our workspace be suspended, and how can I pay manually?', 'expected' => 'billing'],

    // account
    ['subject' => 'Cannot log in after changing my email', 'body' => 'I changed my email address and now the login page says no account exists for it.', 'expected' => 'account'],
    ['subject' => 'Remove a former employee', 'body' => 'Someone on our team left the company. How do I remove their access?', 'expected' => 'account'],
    ['subject' => 'Lost my recovery codes', 'body' => 'I lost my two-factor recovery codes and my phone is getting replaced tomorrow. What should I do?', 'expected' => 'account'],
    ['subject' => 'Signed up twice by mistake', 'body' => 'I accidentally created two accounts with different emails. Can you merge them into one?', 'expected' => 'account'],

    // bug
    ['subject' => 'White screen when uploading a PDF', 'body' => 'Uploading any PDF larger than 5 MB shows a blank page that says "Something went wrong".', 'expected' => 'bug'],
    ['subject' => 'Every notification arrives twice', 'body' => 'Since Monday each email notification is delivered two times, a few seconds apart.', 'expected' => 'bug'],
    ['subject' => 'Monthly report total is wrong', 'body' => 'The total at the bottom of the monthly report does not match the sum of the rows above it.', 'expected' => 'bug'],
    // borderline: bug vs billing
    ['subject' => 'Invoice PDF will not download', 'body' => 'Clicking "Download PDF" on the invoices page returns a 500 error page every time.', 'expected' => 'bug'],

    // feature-request
    ['subject' => 'Export reports to Excel', 'body' => 'Could you add an option to export reports as .xlsx instead of only CSV?', 'expected' => 'feature-request'],
    ['subject' => 'Sync due dates to Google Calendar', 'body' => 'It would really help us if task due dates showed up in our Google Calendar.', 'expected' => 'feature-request'],
    // borderline: feature-request vs billing
    ['subject' => 'Pay by bank transfer', 'body' => 'Our finance team cannot use credit cards. Would you consider supporting payment by bank transfer?', 'expected' => 'feature-request'],
];
