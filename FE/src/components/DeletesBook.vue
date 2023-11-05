<template>
    <div>
        <div class="modal fade" id="deleteManyBook" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Cảnh báo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            Bạn có chắc chắn muốn xóa những sách sau đây khỏi hệ thống không ?
                            <div v-for="(book, index) in books" :key="index">
                                <li v-if="selectedBooks.includes(book.book_id)">
                                    <strong>{{ book.title }}</strong>
                                </li>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" ref="closeButton"
                            id="close">Đóng</button>
                        <button type="button" class="btn btn-outline-danger" @click="deleteBook">Xóa</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import BaseRequest from '../restful/admin/core/BaseRequest';
import useEventBus from '../composables/useEventBus'
export default {
    name: "DeletesBook",
    props: {
        selectedBooks: Array,
        books: Object,
    },
    components: {
    },
    methods: {
        deleteBook: function () {
            const selectedBooksArray = Object.values(this.selectedBooks);
            var data = {
                list_id: selectedBooksArray
            }
            BaseRequest.delete('book/deletes', {data : data})
                .then((data) => {
                    const { emitEvent } = useEventBus();
                    emitEvent('eventSuccess', data.message);
                    window.location.reload();
                })
                .catch((error) => {
                    const { emitEvent } = useEventBus();
                    emitEvent('eventError', error.response.data.message);
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
</style>