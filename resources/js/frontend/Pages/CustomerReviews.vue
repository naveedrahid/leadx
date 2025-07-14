<template>

    <Head title="Testimonials" />
    <MasterLayout :loader="false">
        <section class="hero-section main-section overflow-hidden bg-light-green" id="hero">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-10 col-md-8">
                        <div class="hero-content text-center">
                            <h4 class="subheading">{{ $page.props.app.name }} Success Stories</h4>
                            <h1 class="mb-4">Why Millions <span class="text-primary">Rely on {{ $page.props.app.name }}</span> to Build Powerful WordPress Forms</h1>
                            <Link :href="route('pricing')" class="button button-s2 button-primary button-primary">Get {{ $page.props.app.name }} Now →</Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="main-section testimonials-section overflow-hidden pb-250" id="testimonials">
            <div class="container">
                <div class="row g-4" v-if="tesimonials.length > 0">
                    <!-- START BOX -->
                    <div class="col-lg-4 col-md-4 col-sm-12" v-for="(tesimonial, index) in tesimonials" :key="index">
                        <div class="testimonials-area">
                            <div class="testimonial-item">
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
                    <div class="col-lg-4 col-md-4 col-sm-12" v-for="(tesimonial, index) in tesimonials" :key="index">
                        <div class="testimonials-area">
                            <div class="testimonial-item">
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
                    <div class="col-lg-4 col-md-4 col-sm-12" v-for="(tesimonial, index) in tesimonials" :key="index">
                        <div class="testimonials-area">
                            <div class="testimonial-item">
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
                    <div class="col-lg-4 col-md-4 col-sm-12" v-for="(tesimonial, index) in tesimonials" :key="index">
                        <div class="testimonials-area">
                            <div class="testimonial-item">
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
                    <!-- END BOX -->
                </div>
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

    data(){
        return{
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),

            tesimonials: [
                {
                    rating: 5,
                    text: "LeadX has completely transformed how we manage our customer interactions. The intuitive form builder made it easy to create custom forms that fit our needs perfectly. Our lead generation has increased by 40% since we started using LeadX!",
                    author: "Sarah J.",
                    image: "/_public_assets/testImg/Crystal Raminez.webp",
                    verified: true,
                },
                {
                    rating: 5,
                    text: "Thanks to LeadX, our workflow has become much more efficient. The pre-built form templates saved us a ton of time, and the mobile-friendly forms ensure our clients can reach us from any device. Truly a fantastic tool!",
                    author: "Emily R.",
                    image: "/_public_assets/testImg/Crystal Raminez.webp",
                    verified: true,
                },
                {
                    rating: 5,
                    text: "We've been using LeadX for a few months now, and it's been a game-changer for our business. The smart conditional logic feature allows us to personalize our forms, leading to higher engagement and better data collection. Highly recommend!",
                    author: "John D.",
                    image: "/_public_assets/testImg/Crystal Raminez.webp",
                    verified: true,
                },
            ],
            loader: false,
        };
    }
};
</script>