<template>
    <div>
        <div class="modal fade" ref="addBook" id="addBook" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><span class="mr-2"><i
                                    class="fa-solid fa-plus"></i></span> <strong>Thêm mới sách</strong> </h5>
                    </div>
                    <form @submit.prevent="addBook">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-3 col-form-label">Tên sách </label>
                                        <div class="col-sm-9">
                                            <input required type="text" v-model="book.title" class="form-control"
                                                id="staticEmail" placeholder="Tên sách">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-3 col-form-label">Danh mục</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" v-model="book.id_category">
                                                <option v-for="(category, index) in categories" :value="category.id"
                                                    :key="index">{{ category.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">Ảnh bìa </label>
                                        <div class="col-sm-8">
                                            <div class="dropbox">
                                                <input required class="input-file" type="file" @change="previewImage"
                                                    accept="image/*" ref="fileInput" />
                                                <img class="upload" src='../assets/upload-file.png'
                                                    v-if="previewImageSrc == null">
                                                <div v-if="previewImageSrc" class="box-preview">
                                                    <img class="preview" :src="previewImageSrc" alt="Preview" />
                                                    <img src="../assets/error.png" @click="removeFile" class="close"
                                                        alt="Remove">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-3 col-form-label">Tác giả </label>
                                        <div class="col-sm-9">
                                            <input required type="text" v-model="book.author" class="form-control"
                                                id="staticEmail" placeholder="Tác giả">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-3 col-form-label">Giá </label>
                                        <div class="col-sm-9">
                                            <input required type="number" max="9999999999999" v-model="book.price"
                                                class="form-control" id="staticEmail" placeholder="Giá (VNĐ)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">Năm xuất bản </label>
                                        <div class="col-sm-8">
                                            <VueDatePicker v-model="book.publication_year" year-picker></VueDatePicker>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-4 col-form-label">Nhà xuất bản </label>
                                        <div class="col-sm-8">
                                            <input required type="text" v-model="book.publisher" class="form-control"
                                                id="staticEmail" placeholder="Nhà xuất bản">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-success"><span class="mr-2"><i
                                        class="fa-solid fa-plus"></i></span>Thêm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import BaseRequest from '../restful/admin/core/BaseRequest';
import useEventBus from '../composables/useEventBus';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const { emitEvent } = useEventBus();

export default {
    name: "AddBook",
    props: {
        config: String,
        categories: Object,
    },
    data() {
        return {
            book: {
                title: '',
                author: '',
                id_category: 1,
                publication_year: 2023,
                publisher: null,
                price: 0,
                thumbnail: null,
            },
            previewImageSrc: null,
        }
    },
    components: {
        VueDatePicker,
    },
    computed: {

    },
    methods: {
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewImageSrc = e.target.result;
                    this.book.thumbnail = file;
                };
                reader.readAsDataURL(file);
            } else this.removeFile();
        },
        removeFile: function () {
            this.previewImageSrc = null;
            this.book.thumbnail = null;
            this.$refs.fileInput.value = '';
        },
        addBook: function () {
            const formData = new FormData();
            const fields = ['title', 'author', 'id_category', 'publication_year', 'publisher', 'price', 'thumbnail'];
            for (const field of fields) formData.append(field, this.book[field]);

            BaseRequest.post('book/add', formData)
                .then((data) => {
                    emitEvent('eventSuccess', data.message);
                    const closeAdd = this.$refs.addBook;
                    closeAdd.click();
                    window.location.reload();
                })
                .catch((error) => {
                    if (error.response.data.data) emitEvent('eventError', error.response.data.data[0]);
                    else emitEvent('eventError', 'Thêm sách thất bại !');
                })
        }
    }
}
</script>

<style scoped>
.modal-dialog {
    max-width: 1000px;
}

img {
    max-width: 100%;
    max-height: 100%;
}

li {
    list-style: none;
    padding: 3px 0px;
}

.dropbox {
    width: fit-content;
    max-height: 100px;
    border-radius: 6px;
}

.dropbox:hover {
    background: #E8F5E9;
}

.input-file {
    opacity: 0;
    left: 0px;
    position: absolute;
    cursor: pointer;
    width: 140px;
    height: 80px;
    margin-left: 20px;
}

.preview {
    height: 80px;
}

.upload {
    height: 80px;
}

.box-preview {
    position: relative;
}

.close {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 16px;
}
</style>