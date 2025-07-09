<template>
    <!-- <section class="brand-logos-section overflow-hidden" id="brand-logos">
        <div class="container">
            <div class="brand-logos" v-if="brands.length > 0">
                <div v-for="(brand, index) in brands" :key="index" class="brand-logo-item">
                    <img :src="brand.image" :alt="brand.name" class="img-fluid" />
                </div>
            </div>
        </div>
    </section> -->

    <section class="leadxhome">
        <div class="container">
            <div class="row py-4">
                <div class="slider" v-if="brands.length > 0">
                    <div class="slide-track">
                        <div class="slide" v-for="(brand, index) in brands" :key="index">
                            <img class="img-gray" :src="brand.image" :alt="brand.name" />
                        </div>
                        <div class="slide" v-for="(brand, index) in brands" :key="index">
                            <img class="img-gray" :src="brand.image" :alt="brand.name" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            brands: [
                {
                    name: "Hostinger Logo",
                    image: "/_public_assets/img/brands/hostinger.png",
                },
                {
                    name: "GoDaddy Logo",
                    image: "/_public_assets/img/brands/godaddy.png",
                },
                {
                    name: "DreamHost Logo",
                    image: "/_public_assets/img/brands/dreamhost.png",
                },
                {
                    name: "BlueHost Logo",
                    image: "/_public_assets/img/brands/bluehost.png",
                },
                {
                    name: "HubSpot Logo",
                    image: "/_public_assets/img/brands/hubspot.png",
                },
                {
                    name: "Zapier Logo",
                    image: "/_public_assets/img/brands/zapier.png",
                },
                // {
                //     name: "SAP",
                //     image: "/_public_assets/img/brands/sap.png",
                // },
            ],
        };
    },
};
</script>
