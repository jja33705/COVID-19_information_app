<template>
    <div>
        <Head :title="title" />

        <div class="min-h-screen bg-blueGray-50">
            <nav class="bg-blueGray-50">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-row-reverse h-10">

                        <div class="hidden sm:flex sm:items-center sm:ml-6">

                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative" v-if="$page.props.user">
                                <jet-dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                                        </button>

                                        <span v-else class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                {{ $page.props.user.name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Manage Account
                                        </div>

                                        <jet-dropdown-link :href="route('profile.show')">
                                            Profile
                                        </jet-dropdown-link>

                                        <jet-dropdown-link :href="route('api-tokens.index')" v-if="$page.props.jetstream.hasApiFeatures">
                                            API Tokens
                                        </jet-dropdown-link>

                                        <div class="border-t border-gray-100"></div>

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <jet-dropdown-link as="button">
                                                Log Out
                                            </jet-dropdown-link>
                                        </form>
                                    </template>
                                </jet-dropdown>
                            </div>
                            <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-sm text-gray-700 underline"
                            >
                                Log in
                            </Link>

                            <Link
                                :href="route('register')"
                                class="ml-4 text-sm text-gray-700 underline"
                            >
                                Register
                            </Link>
                        </template>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="my-5">
                <p class="text-6xl font-bold text-center">covid-19 travel</p>
            </div>
            <nav class="bg-white px-8 pt-2 shadow-md">
                <div class="-mb-px flex justify-around">
                    <Link class="text-gray-600 tracking-wide font-semibold py-3 mr-8 text-xl hover:text-gray-800" :href="route('covid')" :class="{ 'border-b-4' : $page.url === '/' }">
                        코로나 정보
                    </Link>
                    <Link class="text-gray-600  tracking-wide font-semibold py-3 mr-8 text-xl hover:text-gray-800" :href="route('tour')" :class="{ 'border-b-4' : $page.url.startsWith('/tour') }">
                        여행지 검색
                    </Link>
                    <a class="text-gray-600 tracking-wide font-semibold py-3 mr-8 text-xl hover:text-gray-800" href="#">
                        여행 후기
                    </a>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
    import JetNavLink from '@/Jetstream/NavLink.vue'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        props: {
            title: String,
        },

        components: {
            Head,
            JetApplicationMark,
            JetDropdown,
            JetDropdownLink,
            JetNavLink,
            JetResponsiveNavLink,
            Link,
        },

        data() {
        },

        methods: {
            switchToTeam(team) {
                this.$inertia.put(route('current-team.update'), {
                    'team_id': team.id
                }, {
                    preserveState: false
                })
            },

            logout() {
                this.$inertia.post(route('logout'));
            },
        },
    })
</script>
