<template>
    <div class="lxfloader" :class="{
        'open': toggle
    }">
        <div class="lxfloader-box">
            <div class="lxfloader-icon"></div>
            <p>Please Wait...</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        toggle: false
    }
}
</script>   

<style>
.lxfloader {
    position: fixed;
    inset: 0;
    background-color: rgb(0 0 0 / 30%);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 100000 !important;
    opacity: 0;
    pointer-events: none;
    transition: all .3s;
}

.lxfloader.open {
    opacity: 1;
    pointer-events: all;
}

.lxfloader .lxfloader-box {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 10px;
    background-color: rgb(255 255 255 / 80%);
    width: 150px;
    height: 150px;
    box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;
    border-radius: 5px;
    transition: all .5s ease;
    transform: scale(0);
}

.lxfloader.open .lxfloader-box {
    transform: scale(1);
}

.lxfloader .lxfloader-box .lxfloader-icon {
    display: inline-block;
    width: 80px;
    height: 80px;
}

.lxfloader .lxfloader-box .lxfloader-icon:after {
    content: " ";
    display: block;
    width: 64px;
    height: 64px;
    margin: 8px;
    border-radius: 50%;
    border: 6px solid #008744;
    border-color: #008744 transparent #008744 transparent;
    animation: lds-dual-ring 1.2s linear infinite;
}

@keyframes lds-dual-ring {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.lxfloader .lxfloader-box p {
    color: #333333;
    font-size: 12px;
    font-weight: 600;
    margin: 0;
}
</style>