<template>
    <div v-if="paginate.last_page>1">
        <ul class="pagination justify-content-start">
            <li class="page-item" v-if="pages>5">
                <a class="page-link" @click="runPagination(1)"><i class="fas fa-angle-double-left"></i></a>
            </li>
            
            <li class="page-item" v-if="pageNum>1">
                <a class="page-link" @click="runPagination(pageNum -= 1)">Previous</a>
            </li>
            <li class="page-item disabled" v-else>
                <span class="page-link">Previous</span>
            </li>
            
            <template v-if="pages>5">
                <template v-for="(page, index) in numbers">
                    <li class="page-item" :class="{ 'active': pageNum === page }">
                        <a class="page-link" @click="runPagination(page)">{{ page }}</a>
                    </li>
                </template>
            </template>
            <template v-else>
                <template v-for="(page, index) in pages">
                    <li class="page-item" :class="{ 'active': pageNum === page }">
                        <a class="page-link" @click="runPagination(page)">{{ page }}</a>
                    </li>
                </template>
            </template>
            
            <li class="page-item" v-if="pageNum<pages">
                <a class="page-link" @click="runPagination(pageNum += 1)">Next</a>
            </li>
            <li class="page-item disabled" v-else>
                <span class="page-link">Next</span>
            </li>
            
            <li class="page-item" v-if="pages>5">
                <a class="page-link" @click="runPagination(paginate.last_page)"><i class="fas fa-angle-double-right"></i></a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: [
        "paginate",
        "url",
        "current"
    ],
    
    data() {
        return {
            pageNum: parseInt(this.current),
            pages: Math.ceil(this.paginate.total / this.paginate.per_page)
        }
    },
    
    computed: {
        numbers() {
            const result = [];
            let current = 1;
            let pages = this.pages;
            if(this.pageNum > 4) {
                current = this.pageNum - 2;
            }

            if(this.pages > (current+4)) {
                pages = (current+4);
            }
            
            if(current > (this.pages-5)) {
                current = (this.pages-5);
            }

            for (let i = current; i <= pages; i++) {
                result.push(i);
            }

            return result;
        }
    },

    methods: {
        runPagination(page) {
            this.$emit('items', page);
        }
    },
}
</script>