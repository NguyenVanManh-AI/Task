<template>
    <div>
        <div class="modal fade" id="detailBook" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><span class="mr-2"><i
                                    class="fa-solid fa-book"></i></span><strong>{{ bookSelected.title }}</strong></h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div>
                                    <img class="mx-auto mb-3" :src="config.URL + bookSelected.book_thumbnail" alt="">
                                </div>
                                <div class="title"><span class="mr-2"><i class="fa-solid fa-bookmark"></i></span> <strong>
                                        {{ bookSelected.title }}</strong></div>
                            </div>
                            <div class="col6">
                                <li class="mg-bottom"><strong>ID : </strong> {{ bookSelected.id }} </li>
                                <li class="mg-bottom"><strong>ISBN : </strong> {{ bookSelected.isbn }} </li>
                                <li class="mg-bottom"><strong>Book Code : </strong> {{ bookSelected.book_code }} </li>
                                <li class="mg-bottom"><strong>Danh mục : </strong> {{ bookSelected.name }} </li>
                                <li class="mg-bottom"><strong>Tác giả : </strong> {{ bookSelected.author }} </li>
                                <li class="mg-bottom"><strong>Năm xuất bản : </strong> {{ bookSelected.publication_year }}
                                </li>
                                <li class="mg-bottom"><strong>Nhà xuất bản : </strong> {{ bookSelected.publisher }} </li>
                                <li class="mg-bottom"><strong>Giá : </strong>{{ formatPrice(bookSelected.price) }}đ</li>
                                <li><strong>Ngày cập nhật : </strong> {{ formatDate(bookSelected.updated_at) }} </li>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" @click="deleteBook">Xóa</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import BaseRequest from '../restful/admin/core/BaseRequest';
import useEventBus from '../composables/useEventBus';

const { emitEvent } = useEventBus();

export default {
    name: "DetailBook",
    props: {
        config: String,
        bookSelected: Object,
        formatPrice: Function,
        truncatedTitle: Function,
        formatDate: Function,
    },
    components: {
    },
    methods: {
        deleteBook: function () {
            BaseRequest.delete('book/delete/' + this.bookSelected.book_id)
                .then((data) => {
                    emitEvent('eventSuccess', data.message);
                    window.location.reload();
                })
                .catch(() => {
                    emitEvent('eventError', 'Xóa sách thất bại !');
                })
            const closeButton = this.$refs.closeButton;
            closeButton.click();
        },
    }
}
</script>

<style scoped>
.modal-header .close {
    outline: none;
}

.modal-title {
    color: #007bff;
}

.modal-dialog {
    max-width: 800px;
}

img {
    max-width: 100%;
    max-height: 100%;
}

li {
    list-style: none;
    padding: 3px 0px;
}

li strong {
    color: rgb(80, 80, 80);
}

.mg-bottom {
    border-bottom: 1px solid rgb(236, 236, 236);
}

#left-detail {
    display: flex;
    justify-content: center;
}

.title {
    color: rgb(96, 96, 96);
    font-style: italic;
}
</style>