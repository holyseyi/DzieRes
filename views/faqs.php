<?php
/**
 * FAQs View
 */
$faqs = [
    ['q' => 'Do you offer delivery?', 'a' => 'Yes! We deliver within a 20km radius. Delivery is free on orders above ₵100, otherwise a ₵15 fee applies.'],
    ['q' => 'Can I make a reservation online?', 'a' => 'Absolutely. Visit our Reservations page to book a table for your preferred date and time.'],
    ['q' => 'Do you cater for events?', 'a' => 'Yes, we offer catering and private event bookings. Contact us through the Events page or Contact form.'],
    ['q' => 'Are there vegetarian/vegan options?', 'a' => 'Our menu includes clearly marked vegetarian and vegan dishes across multiple categories.'],
    ['q' => 'How does the loyalty program work?', 'a' => 'You earn points on every order (10 points per ₵1 spent). Redeem them for discounts and free items.'],
    ['q' => 'What payment methods do you accept?', 'a' => 'We accept Cash, Card, Mobile Money, and Pay on Delivery. This demo uses a simulated gateway.'],
    ['q' => 'Can I cancel or modify my order?', 'a' => 'Orders can be cancelled or modified while they are still pending. Contact us as soon as possible.'],
    ['q' => 'Do you have gluten-free options?', 'a' => 'Many of our dishes can be prepared gluten-free. Please note your preference in special instructions.'],
];
?>
<section class="page-hero">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <p class="section-subtitle">Need Help?</p>
            <h1 class="page-title">FAQs</h1>
            <div class="section-divider mx-auto"></div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <?php foreach ($faqs as $i => $f): ?>
                        <div class="accordion-item glass-card border-0 mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $i ?>">
                                    <?= \icon('question-circle', ['class=" text-gold" style="width:1.1em;height:1.1em;margin-right:0.5rem;vertical-align:-0.15em;"']) ?><?= \escape($f['q']) ?>
                                </button>
                            </h2>
                            <div id="faq<?= $i ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted"><?= \escape($f['a']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-4">
                    <p class="text-muted">Still have questions?</p>
                    <a href="<?= \baseUrl('contact') ?>" class="btn btn-gold">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>
