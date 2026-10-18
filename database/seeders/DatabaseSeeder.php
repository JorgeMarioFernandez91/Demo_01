<?php

namespace Database\Seeders;

use App\Models\HelpArticle;
use App\Models\Tenant;
use App\Models\Ticket;
use App\Models\User;
use App\Support\CurrentTenant;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed two tenants, each with a user, help articles and tickets.
     */
    public function run(): void
    {
        foreach ($this->tenants() as $slug => $data) {
            $tenant = Tenant::create(['name' => $data['name'], 'slug' => $slug]);

            User::factory()->create([
                'tenant_id' => $tenant->id,
                'name' => $data['user'],
                'email' => "admin@{$slug}.test",
            ]);

            app(CurrentTenant::class)->run($tenant, function () use ($data) {
                foreach ($data['articles'] as [$title, $body]) {
                    HelpArticle::create(['title' => $title, 'body' => $body]);
                }

                foreach ($data['tickets'] as [$subject, $body, $category, $status]) {
                    Ticket::create(compact('subject', 'body', 'category', 'status'));
                }
            });
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function tenants(): array
    {
        return [
            'acme' => [
                'name' => 'Acme Cloud',
                'user' => 'Ada Admin',
                'articles' => [
                    ['How to update your payment method', 'Go to Settings → Billing → Payment methods and click "Add card". Set the new card as default, then remove the old one.'],
                    ['Understanding your invoice', 'Invoices are issued on the 1st of each month and list usage per project. Prorated charges appear when you change plans mid-cycle.'],
                    ['Requesting a refund', 'Refunds are available within 14 days of a charge. Open a billing ticket with the invoice number and the reason for the request.'],
                    ['Resetting your password', 'Click "Forgot password" on the login page. The reset link is valid for 60 minutes. Check your spam folder if it does not arrive.'],
                    ['Enabling two-factor authentication', 'Go to Settings → Security and scan the QR code with an authenticator app. Save your recovery codes somewhere safe.'],
                    ['Inviting teammates', 'Owners and admins can invite teammates from Settings → Team. Invitations expire after 7 days.'],
                    ['Reporting a bug', 'Include the steps to reproduce, what you expected, what happened instead, your browser and a screenshot if possible.'],
                    ['How we handle feature requests', 'Feature requests are reviewed every two weeks. Popular requests are added to the public roadmap.'],
                ],
                'tickets' => [
                    ['Charged twice for October', 'I see two identical charges on my card for the October invoice. Please refund one of them.', 'billing', 'open'],
                    ['Need a VAT invoice', 'Our accounting team needs invoices that include our VAT number. How do I add it?', 'billing', 'pending'],
                    ['Downgrade to the Starter plan', 'We would like to downgrade at the end of this billing cycle. Will we lose any data?', 'billing', 'closed'],
                    ['Cannot reset my password', 'The reset email never arrives. I have checked spam and tried twice.', 'account', 'open'],
                    ['Change the account owner', 'Our previous owner left the company. Please transfer ownership to me.', 'account', 'pending'],
                    ['Export button does nothing', 'Clicking "Export CSV" on the reports page shows a spinner forever. Happens in Chrome and Firefox.', 'bug', 'open'],
                    ['Dashboard times are in UTC', 'All timestamps on the dashboard show UTC even though my profile timezone is Europe/Madrid.', 'bug', 'open'],
                    ['Dark mode please', 'It would be great to have a dark theme for the dashboard.', 'feature-request', 'open'],
                    ['Slack integration', 'Can you send ticket notifications to a Slack channel?', 'feature-request', 'closed'],
                    ['General question about the product', 'Is there a way to talk to someone about our use case before we upgrade?', null, 'open'],
                ],
            ],
            'globex' => [
                'name' => 'Globex Retail',
                'user' => 'Grace Globex',
                'articles' => [
                    ['Billing cycles explained', 'Plans renew monthly on your signup date. Annual plans renew on the anniversary of the first payment.'],
                    ['Applying a discount code', 'Enter your code at checkout or under Billing → Promotions. Only one code can be active at a time.'],
                    ['Why was my card declined?', 'Declines are usually due to insufficient funds, an expired card or a bank fraud check. Contact your bank, then retry.'],
                    ['Changing your email address', 'Go to Profile → Email, enter the new address and confirm it from the verification email.'],
                    ['Closing your account', 'Owners can close the account from Settings → Danger zone. Data is deleted after 30 days.'],
                    ['Managing user roles', 'Roles are Owner, Admin and Agent. Only Owners can manage billing.'],
                    ['Known issues', 'We list current known issues and their workarounds on our status page.'],
                    ['Submitting product ideas', 'Use the "Ideas" board in the app to submit and vote on new features.'],
                ],
                'tickets' => [
                    ['Card declined on renewal', 'Our renewal failed with "card declined" but the card works elsewhere.', 'billing', 'open'],
                    ['Discount code not applied', 'I used the code SPRING20 but the invoice shows full price.', 'billing', 'open'],
                    ['Switch to annual billing', 'How do we switch from monthly to annual and what is the discount?', 'billing', 'closed'],
                    ['Locked out after 2FA phone change', 'I got a new phone and lost access to my authenticator app.', 'account', 'open'],
                    ['Add a second admin', 'Can our account have more than one admin?', 'account', 'closed'],
                    ['Search returns no results', 'Searching orders by customer email returns nothing, even for exact matches.', 'bug', 'pending'],
                    ['Mobile layout broken on iPad', 'The sidebar overlaps the content on iPad in portrait mode.', 'bug', 'open'],
                    ['Bulk edit for products', 'We need to change prices on hundreds of products at once.', 'feature-request', 'open'],
                    ['API rate limits', 'What are the API rate limits on the Pro plan?', null, 'pending'],
                    ['Webhooks for refunds', 'Please add a webhook event when a refund is issued.', 'feature-request', 'open'],
                ],
            ],
        ];
    }
}
