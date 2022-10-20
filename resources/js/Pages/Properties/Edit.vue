<template>
    <AuthenticatedLayout>
        <div scroll-region class="md:flex-1 px-4 py-8 md:p-12 md:overflow-y-auto">
            <Head title="Update Property" />
            <h1 class="mb-8 text-3xl font-bold">
                <Link class="text-indigo-400 hover:text-indigo-600" :href="route('properties.index')">Property</Link>
                <span class="text-indigo-400 font-medium pl-2">/</span> Update
            </h1>
            <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
                <form @submit.prevent="submit">
                    <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <InputLabel for="county" value="County" />
                            <TextInput id="county" v-model="form.county" type="text" class="mt-1 block w-full" autofocus required autocomplete="county" placeholder="County" />
                            <InputError class="mt-2" :message="form.errors.county" />
                        </div>
                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <InputLabel for="country" value="Country" />
                            <TextInput id="country" v-model="form.country" type="text" class="mt-1 block w-full" required autofocus autocomplete="country" placeholder="Country" />
                            <InputError class="mt-2" :message="form.errors.country" />
                        </div>
                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <InputLabel for="town" value="Town" />
                            <TextInput id="town" v-model="form.town" type="text" class="mt-1 block w-full" required autofocus autocomplete="town" placeholder="Town"  />
                            <InputError class="mt-2" :message="form.errors.town" />
                        </div>
                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <InputLabel for="zip" value="Postcode" />
                            <TextInput id="zip" v-model="form.zip" type="text" class="mt-1 block w-full" required autofocus autocomplete="zip" placeholder="Postcode"  />
                            <InputError class="mt-2" :message="form.errors.zip" />
                        </div>
                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <InputLabel for="address" value="Displayable Address" />
                            <TextInput id="address" v-model="form.address" type="text" class="mt-1 block w-full" required autofocus autocomplete="address" placeholder="Displayable Address" />
                            <InputError class="mt-2" :message="form.errors.address" />
                        </div>
                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <InputLabel for="num_bedrooms" value="Number of bedrooms" />
                            <TextInput id="num_bedrooms" v-model="form.num_bedrooms" type="number" class="mt-1 block w-full" required autofocus autocomplete="num_bedrooms" placeholder="Number of bedrooms" />
                            <InputError class="mt-2" :message="form.errors.num_bedrooms" />
                        </div>
                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <InputLabel for="num_bathrooms" value="Number of bathrooms" />
                            <TextInput id="num_bathrooms" v-model="form.num_bathrooms" type="number" class="mt-1 block w-full" required autofocus autocomplete="num_bathrooms" placeholder="Number of bathrooms" />
                            <InputError class="mt-2" :message="form.errors.num_bathrooms" />
                        </div>
                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <InputLabel for="type" value="For Sale / For Rent" />
                            <RadioButtonGroup v-model:checked="form.type" :options="saleType"  id="type" />
                            <InputError class="mt-2" :message="form.errors.type" />
                        </div>
                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <InputLabel for="price" value="Price" />
                            <TextInput id="price" v-model="form.price" type="number" class="mt-1 block w-full" required autofocus autocomplete="price" placeholder="Price" />
                            <InputError class="mt-2" :message="form.errors.price" />
                        </div>
                        <div class="pr-6 pb-8 w-full lg:w-1/2">
                            <InputLabel for="property_type_id" value="Property Type" />
                            <select v-model="form.property_type_id" class="form-select mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option :value="null">Choose</option>
                                <option :value="pType.ex_property_type_id" v-for="pType in propertyTypes" :key="pType.ex_property_type_id">{{ pType.title }}</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.property_type_id" />
                        </div>
                        <div class="pr-6 pb-8 w-full">
                            <InputLabel for="description" value="Description" />
                            <TextArea id="description" v-model="form.description" rows="5" cols="30"  class="mt-1 block w-full" required autofocus autocomplete="description" placeholder="Description" />
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>
                        
                        <div class="pr-8 pb-8 w-full lg:w-1/2">
                            <FileInput v-model="form.image_full" :error="form.errors.image_full" type="file" accept="image/*" label="Image" />
                            <!-- <InputError class="mt-2" :message="form.errors.image_full" /> -->
                        </div>
                        <div class="pr-4 pb-8 w-full" v-if="property.image_full_preview" >
                            <InputLabel for="image_full_preview" value="Current Image" />
                            <a class="flex items-center p-2" :href="property.image_full_preview" target="_blank" tabindex="-1">
                                <img class="block" v-if="property.image_thumbnail_preview" :src="property.image_thumbnail_preview" />
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" :loading="form.processing"> Update Property </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import InputError from '@/Components/InputError.vue'
import RadioButtonGroup from '@/Components/RadioButtonGroup.vue'
import FileInput from '@/Components/FileInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import TextArea from '@/Components/TextArea.vue'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'

const props = defineProps({
    propertyTypes: Array,
    saleType: Array,
    property: Object
})

const form = useForm({
    county: props.property.country,
    country: props.property.country,
    town: props.property.town,
    zip: props.property.zip,
    description: props.property.description,
    address: props.property.address,
    num_bedrooms: props.property.num_bedrooms,
    num_bathrooms: props.property.num_bathrooms,
    price: props.property.price,
    property_type_id: props.property.property_type_id,
    type: props.property.type,
    image_full: null
})

const submit = () => {
    form.post(route('properties.update',props.property))
}
</script>
