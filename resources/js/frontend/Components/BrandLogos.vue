<template>
    <section class="brand-logos-section overflow-hidden" id="brand-logos">
        <div class="container">
            <div class="brand-logos" v-if="brands.length > 0">
                <div v-for="(brand, index) in brands" :key="index" class="brand-logo-item">
                    <img :src="brand.image" :alt="brand.name" class="img-fluid" />
                </div>
            </div>
        </div>
    </section>
    <!-- <section class="brand-logos-section overflow-hidden" id="brand-logos">
        <div class="container">
            <div class="brand-logos" v-if="brands.length>0">
                <div v-for="(brand, index) in brands" :key="index" class="brand-logo-item">
                    <img :src="brand.image" :alt="brand.name" class="img-fluid">
                </div>
            </div>
        </div>
    </section> -->
</template>

<script>
export default {
    data() {
        return {
            brands: [
                {
                    name: "Hostinger",
                    image: "/_public_assets/img/brands/hostinger.png",
                },
                {
                    name: "GoDaddy",
                    image: "/_public_assets/img/brands/godaddy.png",
                },
                {
                    name: "DreamHost",
                    image: "/_public_assets/img/brands/dreamhost.png",
                },
                {
                    name: "BlueHost",
                    image: "/_public_assets/img/brands/bluehost.png",
                },
                {
                    name: "HubSpot",
                    image: "/_public_assets/img/brands/hubspot.png",
                },
                {
                    name: "Zapier",
                    image: "/_public_assets/img/brands/zapier.png",
                },
                {
                    name: "SAP",
                    image: "/_public_assets/img/brands/sap.png",
                },
            ],
        };
    },
};
</script>
