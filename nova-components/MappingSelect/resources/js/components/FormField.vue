<template>
    <default-field :field="field">
        <template slot="field">
            <gmap-autocomplete
                class="w-full form-control form-input form-input-bordered"
                @place_changed="setPlace">
            </gmap-autocomplete>
        </template>
    </default-field>
</template>

<script>
    import {FormField, HandlesValidationErrors} from 'laravel-nova'

    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],
        data() {
            return {
                currentPlace: null
            }
        },
        methods: {
            fill(formData) {
                formData.append('land_mark',this.currentPlace.formatted_address);
                formData.append('latitude', this.currentPlace.geometry.location.lat());
                formData.append('longitude', this.currentPlace.geometry.location.lng())
            },
            setPlace(place) {
                console.log(place);
                this.currentPlace = place;
            }
        }
    }
</script>
