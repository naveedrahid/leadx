<template>

    <Head title="Testimonials" />
    <MasterLayout :loader="false">
        <section class="hero-section main-section overflow-hidden bg-light-green" id="hero">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-10 col-md-8">
                        <div class="hero-content text-center">
                            <h4 class="subheading">{{ $page.props.app.name }} Success Stories</h4>
                            <h1 class="mb-4">Why Millions <span class="text-primary">Rely on {{ $page.props.app.name
                                    }}</span> to Build Powerful WordPress Forms</h1>
                            <Link :href="route('pricing')" class="button button-s2 button-primary button-primary">Get {{
                            $page.props.app.name }} Now →</Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="main-section testimonials-section overflow-hidden pb-250" id="testimonials">
            <div class="container">
                <div class="row" v-if="tesimonials.length > 0">
                    <!-- START BOX -->
                    <div class="col-lg-4 col-md-4 col-sm-12 mb-4" v-for="(tesimonial, index) in tesimonials" :key="index" :class="{ testimonialCol: (index + 1) % 5 === 0 }">
                        <div class="testimonials-area">
                            <div class="testimonial-item text-center text-lg-start text-md-start">
                                <div class="testimonial-rate">
                                    <i :class="{
                                        'bi bi-star-fill': tesimonial.rating >= 1,
                                        'bi bi-star': tesimonial.rating < 1,
                                    }"></i>
                                    <i :class="{
                                        'bi bi-star-fill': tesimonial.rating >= 2,
                                        'bi bi-star': tesimonial.rating < 2,
                                    }"></i>
                                    <i :class="{
                                        'bi bi-star-fill': tesimonial.rating >= 3,
                                        'bi bi-star': tesimonial.rating < 3,
                                    }"></i>
                                    <i :class="{
                                        'bi bi-star-fill': tesimonial.rating >= 4,
                                        'bi bi-star': tesimonial.rating < 4,
                                    }"></i>
                                    <i :class="{
                                        'bi bi-star-fill': tesimonial.rating == 5,
                                        'bi bi-star': tesimonial.rating < 5,
                                    }"></i>
                                </div>
                                <p>{{ tesimonial.text }}</p>
                                <div class="testimonial-author">
                                    <h4 class="testimonial-author-name">{{ tesimonial.author }}</h4>
                                    <span class="testimonial-author-verified">Verified Customer</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END BOX -->
            </div>
        </section>
    </MasterLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import MasterLayout from '@/frontend/Layouts/MasterLayout.vue';

export default {
    components: {
        Head,
        Link,
        MasterLayout
    },

    data() {
        return {
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),

            tesimonials: [
                {
                    rating: 5,
                    text: "Since we switched to LeadxForm, we’ve noticed a big improvement in both the number and quality of leads coming in. The conditional logic keeps things relevant for each user, and it’s really helped us qualify leads more effectively.",
                    author: "-James R",
                    verified: true
                },
                {
                    rating: 5,
                    text: "Thanks to LeadxForm, which has made collecting leads so much easier. We have already seen a noticeable increase in conversions just by improving our form flow.",
                    author: "-Sarah M",
                    verified: true
                },
                {
                    rating: 5,
                    text: "We would definitely accept that this platform has streamlined our client intake process. What used to take hours to sort through is now happening in real time.",
                    author: "-Ben T",
                    verified: true
                },
                {
                    rating: 5,
                    text: "LeadxForm has been a huge win for our team. The mobile responsiveness feature means that the client can fill out our forms on any device, which is essential these days. I highly recommend the platform as it has made our brand look professional.",
                    author: "-Emma W",
                    verified: true
                },
                {
                    rating: 5,
                    text: "Without any doubt, LeadxForm has been a solid rock for our business. We integrated it into our CRM in minutes and everything works seamlessly. It is reliable and exactly what we needed to scale our lead collection.",
                    author: "-Daniel P",
                    verified: true
                },
                {
                    rating: 5,
                    text: "I am not a tech person but setting up forms with LeadxForm was surprisingly easy. In no time, we had everything customised through pre-built templates. Their support team was also quick to assist whenever we had questions.",
                    author: "-Liam S",
                    verified: true
                },
                {
                    rating: 5,
                    text: "As a small business owner, I needed a solution that didn't require a developer and LeadxFrom did it. We created responsive forms that look great and perform better. It’s an amazing platform.",
                    author: "-Nina H",
                    verified: true
                },
                {
                    rating: 5,
                    text: "We get a lot of enquiries every day in real estate, and LeadxForms has really helped us stay organised. Being able to automatically sort leads by property type has saved us so much time. Honestly, it’s one of the most useful tools we’ve added recently.",
                    author: "-Omar Z",
                    verified: true
                },
                {
                    rating: 5,
                    text: "We were really happy with how easy it was to add LeadxForm to our landing pages. It fits right in with our site and looks great, clean, simple, and professional. Honestly, it just made things way easier for us.",
                    author: "-Tina L",
                    verified: true
                },
                {
                    rating: 5,
                    text: "We have tested a lot of form builders over the years, but none of them compare to the flexibility and speed of LeadxForm. The dashboard is designed to provide us with the real insights into the conversion trends and drop-off. It is truly a well-thought-out platform.",
                    author: "-Damien S",
                    verified: true
                },
                {
                    rating: 5,
                    text: "Shoutout to the LeadxForm crew. We had a last-minute campaign and their templates were a lifesaver, so we managed to launch in under an hour. Turned out to be the best-performing campaign we had.",
                    author: "-Rachel K",
                    verified: true
                },
                {
                    rating: 5,
                    text: "We have been using LeadxFrom for multiple departments like sales, customer support, and even HR. The form duplication and user permissions make it really easy for us to manage. Honestly, it has become part of our operations now.",
                    author: "-Jason W",
                    verified: true
                },
                {
                    rating: 5,
                    text: "We needed something with logic built in but didn’t want the tech headaches and LeadxForm was perfect for that. Support was great too, really helpful during setup and made the whole thing easy to get going.",
                    author: "-TY R",
                    verified: true
                },
                {
                    rating: 5,
                    text: "LeadxForm is the answer if you need logic without the tech headache. Big thanks to the customer support for helping us integrate it with flawless execution.",
                    author: "-Anika G",
                    verified: true
                },
                {
                    rating: 5,
                    text: "LeadxForm is one of those tools you didn't know you needed until you tried it. We use it to qualify leads based on the budget and location before they even enter our inbox. It has made our sales team more operative.",
                    author: "-Sean D",
                    verified: true
                },
                {
                    rating: 5,
                    text: "We love how customisable LeadxForm is. From fonts and colours to field logic, it gives us total control.",
                    author: "-Clara M",
                    verified: true
                },
                {
                    rating: 5,
                    text: "We have used a lot of form tools over the years but LeadxForm has been by far the most user-friendly. The setup was easy and now it has become a key part of our lead process.",
                    author: "-Luaren D",
                    verified: true
                },
                {
                    rating: 5,
                    text: "As a digital marketing agency, we heavily rely on forms to gather client briefs and upcoming feedback. LeadxForm lets us customise everything and our clients love how easy it is to fill the forms out now.",
                    author: "-Zain K",
                    verified: true
                },
                {
                    rating: 5,
                    text: "We were in search of something competent yet lightweight and LeadxForm ticked all the boxes. It is rare to find tools that do what they promise, unlike this platform.",
                    author: "-Chloe F",
                    verified: true
                },
                {
                    rating: 5,
                    text: "We have started using LeadxForm to handle appointment requests and new patient forms at our clinic. It has made things so much easier. No more chasing missing details or dealing with paper forms. Everything’s secure, simple, and it just works.",
                    author: "-Dr. Nabil H",
                    verified: true
                },
                {
                    rating: 5,
                    text: "Honestly, we didn't really expect much when we first tried it, but LeadxForm blew us away. We are not seeing better leads and fewer abandoned forms.",
                    author: "-Sophie M",
                    verified: true
                },
                {
                    rating: 5,
                    text: "Big thanks to the LeadxForm team who have made our event registration collection easier and better in all aspects. Their forms are super easy to customise!",
                    author: "-Andre T",
                    verified: true
                },
                {
                    rating: 5,
                    text: "I run a tutoring business and I use LeadxForm to handle student signups. It saves me time, at least an hour a day and I love the fact that it works well on the phone.",
                    author: "-Ahmed S",
                    verified: true
                },
                {
                    rating: 5,
                    text: "We have added LeadxForm to all our contact pages and it has been working flawlessly. Really happy with how it has been performing.",
                    author: "-Grace J",
                    verified: true
                },
                {
                    rating: 5,
                    text: "It is amazing to go from manual forms to fully automated systems. No more copying or pasting and a bulk of messy work. LeadxForm turned out to be a total time-saver for the whole team.",
                    author: "-Noah R",
                    verified: true
                },
                {
                    rating: 5,
                    text: "We run a training business and use LeadxForm for all our course signups. It is great being able to collect specific information from the participants before the session through uncomplicated forms.",
                    author: "-Helen C",
                    verified: true
                },
                {
                    rating: 5,
                    text: "With LeadxForm, we are capturing better data, as our forms feel more interactive. They provide us a smarter way to collect info.",
                    author: "-Danielle M",
                    verified: true
                },
                {
                    rating: 5,
                    text: "LeadxForm works. There are no fluff or confusing features but a solid tool that delivers what it is supposed to.",
                    author: "-Isaac M",
                    verified: true
                },
                {
                    rating: 5,
                    text: "For our non-profit organisation, it has been a great tool for volunteer signups and donation forms. It is simple to use and still appears to be professional. We love how clean the form looks.",
                    author: "-Rachel N",
                    verified: true
                }
            ],
            loader: false,
        };
    }
};
</script>