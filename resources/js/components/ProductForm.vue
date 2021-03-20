<template xmlns="http://www.w3.org/1999/html">
    <div class="form-group">
        <p class="h5">{{ title }}</p>
        <form action="#" class="form-row" @submit.prevent="formAction">
            <div class="col-lg-4">
                <input type="text" name="name" id="name" class="form-control" placeholder="Full Name" v-model="formData.name" />
            </div>

            <div class="col-lg-4">
                <input type="number" min="1" name="quantity" id="quantity" class="form-control" placeholder="Quantity" v-model="formData.quantity" />
            </div>

            <div class="col-lg-4">
                <textarea name="description" id="description" class="form-control" placeholder="Description" v-model="formData.description" rows="4"></textarea>
            </div>

            <div class="col-lg-2">
                <button type="submit" class="btn btn-primary btn-block">Save</button>
            </div>
            <div class="col-lg-2">
                <button type="button" class="btn btn-danger btn-block" @click="cancel">Cancel</button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: "ProductForm",
        props: {
            formAction: {
                type: Function
            },
            cancel: {
                type: Function
            },
            formData: {
                type: Object
            },
            title: {
                String
            }
        }
    }
</script>
