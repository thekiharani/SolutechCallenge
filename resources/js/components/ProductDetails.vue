<script>
    export default {
        name: "ProductDetails",
        props: {
            product: {
                type: Object
            }
        },
        methods: {
            showDetailsModal() {
                $('#productDatailsModal').modal('show')
            }
        }
    }
</script>


<!-- Modal -->
<template>
    <div class="modal fade" id="productDatailsModal" tabindex="-1" aria-labelledby="productDatailsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productDatailsModalLabel">
                            Product Details
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="#">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Product Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Product Name" v-model="formData.name">
                            </div>

                            <div class="form-group">
                                <label for="quantity">Product Quantity</label>
                                <input type="number" min="1" name="quantity" id="quantity" class="form-control" placeholder="Product Quantity" v-model="formData.quantity">
                            </div>

                            <div class="form-group">
                                <label for="description">Product Description</label>
                                <textarea rows="4" name="description" id="description" class="form-control" placeholder="Product Description" v-model="formData.description"></textarea>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <span v-if="edit">
                                <button type="button" class="btn btn-primary" @click.prevent="updateProduct(productID = edit_product.id)">Update</button>
                            </span>
                            <span v-else>
                                <button type="button" class="btn btn-success" @click.prevent="storeProduct">Save</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</template>
