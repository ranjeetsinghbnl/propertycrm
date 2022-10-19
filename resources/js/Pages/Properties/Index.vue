<template>
    <div>
        <Head title="Properties" />
        <div scroll-region class="md:flex-1 px-4 py-8 md:p-12 md:overflow-y-auto">
            <div>
                <h1 class="mb-8 font-bold text-3xl">Properties</h1>
            </div>
            <flash-messages />
            <div class="flex items-center justify-between mb-6">
                <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
                    <label class="block text-gray-700">Source</label>
                    <select v-model="form.source" class="form-select mt-1 w-full">
                        <option :value="null">Choose</option>
                        <option value="api">API</option>
                        <option value="manual">Manually Added</option>
                    </select>
                    <label class="block text-gray-700 pt-2">Property Type</label>
                    <select v-model="form.type" class="form-select mt-1 w-full">
                        <option :value="null">Choose</option>
                        <option value="sale">For Sale</option>
                        <option value="rent">For Rent</option>
                    </select>
                    <label class="block text-gray-700 pt-2">Type</label>
                    <select v-model="form.property_type_id" class="form-select mt-1 w-full">
                        <option :value="null">Choose</option>
                        <option :value="pType.ex_property_type_id" v-for="pType in propertyTypes" :key="pType.ex_property_type_id">{{pType.title}}</option>
                    </select>
                </search-filter>
                <Link class="btn-indigo" :href="route('properties.create')">
                    <span>Create</span>
                    <span class="hidden md:inline">&nbsp;Property</span>
                </Link>
            </div>
            <div class="bg-white rounded-md shadow overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <tr class="text-left font-bold">
                        <th class="pb-4 pt-6 px-6">County</th>
                        <th class="pb-4 pt-6 px-6">Country</th>
                        <th class="pb-4 pt-6 px-6">Town</th>
                        <th class="pb-4 pt-6 px-6">Postcode</th>
                        <th class="pb-4 pt-6 px-6">Address</th>
                        <th class="pb-4 pt-6 px-6">Bedrooms</th>
                        <th class="pb-4 pt-6 px-6">Bathrooms</th>
                        <th class="pb-4 pt-6 px-6">Price</th>
                        <th class="pb-4 pt-6 px-6">Sale/Rent</th>
                        <th class="pb-4 pt-6 px-6">Property Type</th>
                        <th class="pb-4 pt-6 px-6">Source</th>
                        <th class="pb-4 pt-6 px-6" style="width: 50px">Action</th>
                    </tr>
                    <tr v-for="property in properties.data" :key="property.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t px-6 py-4">
                            <!-- <img v-if="property.photo" class="block -my-2 mr-2 w-5 h-5 rounded-full" :src="property.photo" /> -->
                            {{ property.county }}
                            <!-- <icon v-if="property.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" /> -->
                        </td>
                        <td class="border-t px-6 py-4">{{ property.country }}</td>
                        <td class="border-t px-6 py-4">{{ property.town }}</td>
                        <td class="border-t px-6 py-4">{{ property.zip ? property.zip : 'N/A' }}</td>
                        <td class="border-t px-6 py-4">{{ property.address }}</td>
                        <td class="border-t px-6 py-4">{{ property.num_bedrooms }}</td>
                        <td class="border-t px-6 py-4">{{ property.num_bathrooms }}</td>
                        <td class="border-t px-6 py-4">{{ $filters.currency(property.price) }}</td>
                        <td class="border-t px-6 py-4 capitalize">{{ property.type }}</td>
                        <td class="border-t px-6 py-4">{{ property.type_details }}</td>
                        <td class="border-t px-6 py-4 uppercase">{{ property.source }}</td>
                        <td class="border-t flex items-center px-6 py-4">
                            <Link class="flex items-center px-2" :href="route('properties.edit', property)" tabindex="-1">
                                <icon name="pencil" class="block w-6 h-6 fill-gray-400" />
                            </Link>
                            <Link class="flex items-center" @click="destroy(property.id)" as="button" type="button">
                                <icon name="trash" class="block w-6 h-6 fill-red-600" />
                            </Link>
                        </td>
                    </tr>
                    <tr v-if="properties.data.length === 0">
                        <td class="px-6 py-4 border-t text-center" colspan="12">No properties found.</td>
                    </tr>
                </table>
            </div>
            <pagination class="mt-6" :links="properties.links" />
        </div>
    </div>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { Head, Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Components/Icon.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import SearchFilter from '@/Components/SearchFilter.vue'
import Pagination from '@/Components/Pagination.vue'
import FlashMessages from '@/Components/FlashMessages.vue'

export default {
    components: {
        Head,
        Icon,
        Link,
        SearchFilter,
        Pagination,
        FlashMessages,
    },
    layout: AuthenticatedLayout,
    props: {
        filters: Object,
        properties: Object,
        propertyTypes: Array
    },
    data() {
        return {
            form: {
                search: this.filters.search,
                source: this.filters.source,
                type: this.filters.type,
                property_type_id: this.filters.property_type_id
            },
        }
    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/properties', pickBy(this.form), { preserveState: true })
            }, 150),
        },
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        },
        destroy(id) {
            if (confirm('Are you sure you want to delete this property?') && id) {
                this.$inertia.delete(`/properties/${id}`)
            }
        },
    },
}
</script>
