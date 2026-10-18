<?php

/*
 * Sample tickets for `php artisan support:eval`, each with the category we expect.
 * These are fixtures, not tenant records, and are deliberately different from the
 * seeded tickets. Borderline cases are marked so a miss there reads as ambiguity.
 */

return [
    // billing
    ['subject' => 'Refund for unused months', 'body' => 'We prepaid for a year but are shutting the project down. Can we get the remaining months refunded?', 'expected' => 'billing'],
    ['subject' => 'Sales tax added to invoice', 'body' => 'Our latest invoice suddenly includes sales tax. We are tax exempt and sent the certificate last year.', 'expected' => 'billing'],
    ['subject' => 'Upgrade mid-cycle', 'body' => 'If we move from Starter to Pro today, are we charged the full price or only for the rest of the month?', 'expected' => 'billing'],
    // borderline: billing vs account
    ['subject' => 'Finance team needs invoice access', 'body' => 'Our accountant should be able to download invoices but nothing else. How do we set that up?', 'expected' => 'billing'],

    // account
    ['subject' => 'Password reset link expired', 'body' => 'The password reset link says it has expired, even though I clicked it right away.', 'expected' => 'account'],
    ['subject' => 'Transfer workspace ownership', 'body' => 'I am leaving the company next week. How do I make my colleague the owner of our workspace?', 'expected' => 'account'],
    ['subject' => 'Change the name on my profile', 'body' => 'I recently got married and want my profile to show my new last name.', 'expected' => 'account'],
    // borderline: account vs bug
    ['subject' => 'Two-factor code never accepted', 'body' => 'I set up two-factor yesterday and now every code from my authenticator app is rejected, so I cannot sign in.', 'expected' => 'account'],

    // bug
    ['subject' => 'Dates shown one day off', 'body' => 'Due dates entered as March 3 show up as March 2 in the list view. The detail view is correct.', 'expected' => 'bug'],
    ['subject' => 'Search crashes with an apostrophe', 'body' => "Searching for O'Brien shows an error page. Any search with an apostrophe fails.", 'expected' => 'bug'],
    ['subject' => 'Attachments missing after save', 'body' => 'I attach a file to a ticket, click save, and the attachment is gone when the page reloads.', 'expected' => 'bug'],
    // borderline: bug vs billing
    ['subject' => 'Usage page shows wrong seat count', 'body' => 'The usage page says we have 14 seats but we only have 9 people. Every page counts 9 except this one.', 'expected' => 'bug'],

    // feature-request
    ['subject' => 'Custom fields on tickets', 'body' => 'We would love to add our own fields, like order number, to each ticket.', 'expected' => 'feature-request'],
    ['subject' => 'Single sign-on with Okta', 'body' => 'Is SAML single sign-on with Okta on the roadmap? Our security team requires it.', 'expected' => 'feature-request'],
    // borderline: feature-request vs billing
    ['subject' => 'Invoices in euros', 'body' => 'We are based in Germany. Could you offer pricing and invoices in euros instead of dollars?', 'expected' => 'feature-request'],
];
