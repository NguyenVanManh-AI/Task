<template>
    <div>
        <div class="modal fade" id="deleteBook" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                            Bạn có chắc chắn muốn xóa Sách <strong>{{ bookSelected.title }}</strong> khỏi hệ thống không ?
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
import useEventBus from '../composables/useEventBus';

const { emitEvent } = useEventBus();

export default {
    name: "DeleteBook",
    props: {
        bookSelected: Object
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
</style>