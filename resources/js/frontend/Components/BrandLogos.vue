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
            <p class="text-center mt-5">Recommended and Used by the top brands around the world</p>
            <div class="row pb-5">
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
                    image: "/_public_assets/img/brands/image 176.svg",
                },
                {
                    name: "GoDaddy Logo",
                    image: "/_public_assets/img/brands/image 180.svg",
                },
                {
                    name: "DreamHost Logo",
                    image: "/_public_assets/img/brands/image 178.svg",
                },
                {
                    name: "BlueHost Logo",
                    image: "/_public_assets/img/brands/image 179.svg",
                },
                {
                    name: "HubSpot Logo",
                    image: "/_public_assets/img/brands/image 175.svg",
                },
                {
                    name: "Zapier Logo",
                    image: "/_public_assets/img/brands/image 177.svg",
                },
                {
                    name: "Zapier Logo",
                    image: "/_public_assets/img/brands/image 174.svg",
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
