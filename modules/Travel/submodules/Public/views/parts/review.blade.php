<section id="review">
<v-card class="elevation-0 transparent hidden-sm-and-down">
    <v-parallax class="mb-4 mt-5 " height="450" src="{{ assets('frontier/images/public/roadtrip/5.jpg') }}">
        <v-layout
            column
            align-left
            justify-center
            class="white--text"
            >
            <div class="insert-overlay" style="background: rgba(0, 0, 0, 0.5); position: absolute; width: 100%; height: 100%; top: 0;"></div>
            <v-card dark class="elevation-0 transparent card--flex">
                <v-container fluid grid-list-lg>
                    <v-layout row wrap align-center justify-center>
                        <v-flex lg10 sm12 xs12>
                            <v-layout row wrap align-top justify-top>
                                <v-flex md3 xs12>
                                    <h4><strong>{!! settings('review_title') !!}</strong></h4>
                                    <h2 class="subheading">{!! settings('review_subtitle') !!}</h2>
                                </v-flex>
                                <v-flex md9 xs12>
                                    <v-layout row wrap>
                                        <v-flex md4 xs12 v-for="item in reviews" :key="item.id">
                                            <v-card class="elevation-1">
                                                <v-card-media
                                                    height="80px"
                                                    :src="item.src"
                                                    class="primary lighten-4">
                                                </v-card-media>
                                                <v-toolbar class="elevation-0 transparent"></v-toolbar>
                                                <v-card class="elevation-0 transparent text-xs-center review--flex">
                                                    <v-avatar size="80px" class="">
                                                        <img :src="item.useravatar" alt="" style="border: 3px solid #fff;">
                                                    </v-avatar>
                                                </v-card>

                                                <v-card-text class="text-xs-center">
                                                    <div class="body-2">@{{ item.user.fullname }}</div>
                                                    <div class="caption primary--text">@{{ item.reviewable.name }}</div>
                                                    <div class="mt-2 grey--text"><v-icon class="caption mr-2 mb-2">fa-quote-left</v-icon> <div v-html="item.excerpt"></div> <v-icon class="caption ml-2 mb-2">fa-quote-right</v-icon></div>
                                                </v-card-text>
                                            </v-card>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card>
        </v-layout>
    </v-parallax>
</v-card>

{{-- Small viewport --}}
<v-card class="elevation-0 transparent hidden-md-and-up">
    <v-container fluid grid-list-lg>
        <v-card-text class="text-xs-center my-3">
            <h2 class="display-1">{!! settings('review_title') !!}</h2>
            <h2 class="subheading grey--text text--darken-1">{!! settings('review_subtitle') !!}</h2>
        </v-card-text>

        <v-layout row wrap>
            <v-flex sm4 xs12 v-for="item in reviews">
                <v-card class="elevation-1">
                    <v-card-media
                        height="150px"
                        :src="item.src"
                        class="grey lighten-4">
                    </v-card-media>
                    <v-toolbar class="elevation-0 transparent"></v-toolbar>
                    <v-card class="elevation-0 transparent text-xs-center review--flex">
                        <v-avatar size="80px" class="">
                            <img :src="item.useravatar" alt="" style="border: 3px solid #fff;">
                        </v-avatar>
                    </v-card>

                    <v-card-text class="text-xs-center">
                        <div class="body-2">@{{ item.fullname }}</div>
                        <div class="caption primary--text">@{{ item.trip }}</div>
                        <div class="mt-2"><v-icon class="caption mr-2 mb-2">fa fa-quote-left</v-icon> @{{ item.text }} <v-icon class="caption ml-2 mb-2">fa fa-quote-right</v-icon></div>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</v-card>
</section>

@push('css')
    <style>
        /*.card--flex {
            margin-top: -300px;
        }
        .review--flex {
            margin-top: -100px;
        }*/
        #review .parallax__content {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
    </style>
@endpush


@push('pre-scripts')
    <script>
        Vue.use(VueResource);

        mixins.push({
            data () {
                return {
                    reviews: {!! json_encode(settings('homepage_reviews', [])) !!}
                }
            },

            mounted () {
                let query = {
                        descending: true,
                        // page: 1,
                        sort: 'created_at',
                        take: 3,
                        group_by: 'user_id',
                    };

                this.reviews = {!! json_encode(\Review\Models\Review::whereIn('id', settings('homepage_reviews', []) ? unserialize(settings('homepage_reviews', [])) : [])->get()->toArray()) !!};

                // this.api().get('{{ route('api.reviews.all') }}', query)
                    // .then((data) => {
                        // this.reviews = data.items.data;
                        // console.log("REV", data.items);
                    // });
            }
        });
    </script>
@endpush
