<template>
    <div id="main">
        <div class="row m-0 pb-2 d-flex justify-content-end" id="search-sort">
            <div class="col-1 pl-0" id="page">
                <select class="form-control" v-model="perPage">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </select>
            </div>
            <div class="col-2 pl-0">
                <select class="form-control" v-model="typesort">
                    <option value="new">Mới nhất</option>
                    <option value="price">Giá</option>
                    <option value="title">Tên</option>
                </select>
            </div>
            <div class="col-2 pl-0">
                <select class="form-control" v-model="sortlatest">
                    <option value="false">Tăng dần</option>
                    <option value="true">Giảm dần</option>
                </select>
            </div>
            <div class="col-2 pl-0">
                <select class="form-control" v-model="selectedCategory">
                    <option value="" selected>Tất cả</option>
                    <option v-for="(category, index) in categories" :value="category.id" :key="index">{{ category.name }}
                    </option>
                </select>
            </div>
            <div class="col-3 pl-0">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></div>
                    </div>
                    <input v-model="search" type="text" class="form-control" id="inlineFormInputGroup"
                        placeholder="Seach...">
                </div>
            </div>
            <div class="pr-1">
                <div class="input-group">
                    <button data-toggle="modal"
                            data-target="#addBook" type="button" class="btn btn-success"><i class="fa-solid fa-plus"></i></button>
                </div>
            </div>
            <div class="pr-0" v-if="selectedBooks.length > 0">
                <div class="input-group">
                    <button data-toggle="modal" data-target="#deleteManyBook" type="button" class="btn btn-danger"><i
                            class="fa-solid fa-trash"></i></button>
                </div>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col"><input ref="selectAllCheckbox" @change="selectAll()" type="checkbox" class=""></th>
                    <th scope="col">#</th>
                    <th scope="col">ISBN</th>
                    <th scope="col">Book Code</th>
                    <th scope="col">Tên sách</th>
                    <th scope="col">Ảnh bìa</th>
                    <th scope="col">Danh mục</th>
                    <th scope="col">Tác giả</th>
                    <th scope="col">Năm xuất bản</th>
                    <th scope="col">Nhà xuất bản</th>
                    <th scope="col">Giá</th>
                    <th scope="col">Cập nhật</th>
                    <th scope="col">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(book, index) in books" :key="index">
                    <th scope="row"><input :checked="isSelected(book.book_id)" type="checkbox" class=""
                            @change="handleSelect(book.book_id)"></th>
                    <th scope="row">{{ book.book_id }}</th>
                    <td>{{ book.isbn }}</td>
                    <td>{{ book.book_code }}</td>
                    <td>{{ truncatedTitle(book.title) }}</td>
                    <td>
                        <div>
                            <img :src="config.URL + book.book_thumbnail" alt="">
                        </div>
                    </td>
                    <td>{{ book.name }}</td>
                    <td>{{ book.author }}</td>
                    <td>{{ book.publication_year }}</td>
                    <td>{{ book.publisher }}</td>
                    <td>{{ formatPrice(book.price) }}đ</td>

                    <td>{{ formatDate(book.updated_at)}}</td>
                    <td>
                        <button type="button" @click="selectBook(book)" class="btn btn-primary mr-1" data-toggle="modal"
                            data-target="#detailBook"><i class="fa-solid fa-eye"></i></button>
                        <button  @click="selectBook(book)" type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#editBook"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button @click="selectBook(book)" type="button" class="btn btn-danger mt-1" data-toggle="modal"
                            data-target="#deleteBook"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div id="divpaginate">
            <paginate :page-count="Math.ceil(this.total / this.perPage)" :page-range="3" :margin-pages="2"
                :click-handler="clickCallback" :initial-page="this.page" :prev-text="'Prev'" :next-text="'Next'"
                :container-class="'pagination'" :page-class="'page-item'">
            </paginate>
        </div>
        <DeleteBook :bookSelected="bookSelected"></DeleteBook>
        <DeletesBook :selectedBooks="selectedBooks" :books="books"></DeletesBook>
        <DetailBook 
            :config="config"
            :bookSelected="bookSelected"
            :format-price="formatPrice" 
            :truncated-title="truncatedTitle"
            :formatDate="formatDate"
        ></DetailBook>
        <AddBook :config="config" :categories="categories"></AddBook>
        <EditBook :config="config" :categories="categories" :bookSelected="bookSelected"></EditBook>

    </div>
</template>

<script>

import BaseRequest from '../restful/admin/core/BaseRequest';
import useEventBus from '../composables/useEventBus';
import config from '../config.js';
import Paginate from 'vuejs-paginate-next';
import DeleteBook from './DeleteBook';
import DeletesBook from './DeletesBook';
import DetailBook from './DetailBook';
import AddBook from './AddBook';
import EditBook from './EditBook';

export default {
    name: "BookAdmin",
    components: {
        paginate: Paginate,
        DeleteBook,
        DeletesBook,
        DetailBook,
        AddBook,
        EditBook,
    },
    setup() {
        document.title = "Library - Book"
    },
    data() {
        return {
            config: config,

            total: 0,
            perPage: 5,
            page: 1,
            selectedCategory: '',
            typesort: 'new',
            sortlatest: 'true',
            search: '',
            query: '',

            categories: [],
            books: [],

            bookSelected: {
                title:'',
                price:0,
                updated_at:'',
            },
            selectedBooks: [],
        }
    },
    mounted() {
        BaseRequest.get('category')
            .then((data) => {
                this.categories = data.data;
                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess', data.message);
            })
            .catch(() => {
                const { emitEvent } = useEventBus();
                emitEvent('eventError', 'Lỗi khi lấy dữ liệu');
            });

        const queryString = window.location.search;
        const searchParams = new URLSearchParams(queryString);
        this.perPage = parseInt(searchParams.get('paginate')) || 5;
        this.selectedCategory = searchParams.get('category_id') || '';
        this.typesort = searchParams.get('typesort') || 'new';
        this.sortlatest = searchParams.get('sortlatest') || 'true';
        this.search = searchParams.get('search') || '';

        this.getBooks();

    },

    methods: {

        getBooks: function () {
            this.query = '?search=' + this.search + '&typesort=' + this.typesort + '&sortlatest=' + this.sortlatest
                + '&category_id=' + this.selectedCategory + '&paginate=' + this.perPage + '&page=' + this.page;
            window.history.pushState({}, null, this.query);

            BaseRequest.get('book' + this.query)
                .then((data) => {
                    this.books = data.data.data;
                    this.total = data.data.total;
                })
                .catch(() => {
                    const { emitEvent } = useEventBus();
                    emitEvent('eventError', 'Lỗi khi lấy dữ liệu');
                });
        },

        formatPrice: function (price) {
            return price.toLocaleString();
        },
        truncatedTitle(title) {
            const maxLength = 150;
            if (title.length > maxLength) return title.slice(0, maxLength) + '...';
            else return title;
        },
        formatDate: function (date) {
            return date.split('T')[0] 
        },

        clickCallback: function (pageNum) {
            this.page = pageNum;
        },

        handleSearchSelect() {
            this.page = 1;
            this.getBooks();
        },

        selectBook: function (bookSelected) {
            this.bookSelected = bookSelected;
        },

        // delete many 
        isSelected(bookId) {
            return this.selectedBooks.includes(bookId);
        },
        handleSelect: function (book_id) {
            const index = this.selectedBooks.indexOf(book_id);
            if (index === -1) this.selectedBooks.push(book_id);
            else this.selectedBooks.splice(index, 1);
        },
        selectAll: function () {
            const checkbox = this.$refs.selectAllCheckbox;
            if (checkbox.checked) this.selectedBooks = this.books.map(book => book.book_id);
            else this.selectedBooks = [];
        }

    },
    watch: {
        'search': 'handleSearchSelect',
        'perPage': 'handleSearchSelect',
        'selectedCategory': 'handleSearchSelect',
        'page': 'getBooks',
        'typesort': 'getBooks',
        'sortlatest': 'getBooks',
    }
}
</script>

<style scoped>
#main {
    padding: 10px 30px;
}

#page {
    margin-right: auto;
}

table {
    font-size: 12px;
}

table img {
    max-width: 60px;
    max-width: 60px;
    object-fit: contain;
}

table button {
    padding: 1px 3px;
    margin-right: 2px;
}
</style>
