<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Credentials
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <button @click="openModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">+ Credential</button>
                        <table class="table-auto w-full hidden md:table">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2">Application</th>
                                    <th class="px-4 py-2">Username</th>
                                    <th class="px-4 py-2 w-40">Password</th>
                                    <!-- <th class="px-4 py-2">Date Created</th>
                                    <th class="px-4 py-2">Date Updated</th> -->
                                    <th class="px-4 py-2 w-40">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="credential in credentials" :key="credential.id">
                                    <td class="border px-4 py-2"> <a v-if="credential.link!=null" :href="credential.link" class="text-blue-500" target="_blank">{{ credential.application }}</a></td>
                                    <td class="border px-4 py-2">{{ credential.username }}</td>
                                    <td class="border px-4 py-2"><button @click="getPassword(credential)" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 w-full rounded">Password</button></td>
                                    <!-- <td class="border px-4 py-2">{{ credential.created_at }}</td>
                                    <td class="border px-4 py-2">{{ credential.updated_at }}</td> -->
                                    <td class="flex flex-row border px-4 py-2 ">
                                        <button @click="edit(credential)"  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-14 mr-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>
                                        <button @click="deleteRow(credential)"  class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-14">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="block md:hidden">
                            <div v-for="credential in credentials" :key="credential.id" class="bg-white border p-6 shadow-xl mb-3 rounded">
                                <label for="">Application</label>:
                                {{ credential.application }}<br>
                                <label for="">Username</label>:
                                    {{ credential.username }}
                                <button @click="getPassword(credential)" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 w-full rounded">
                                    Password
                                </button>
                            </div>
                        </div>
                        <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpen">
                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                                <div class="fixed inset-0 transition-opacity">
                                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                </div>
                                <!-- This element is to trick the browser into centering the modal contents. -->
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
                                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                    <form>
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <div class="">
                                                <div class="mb-4">
                                                    <label for="application" class="block text-gray-700 text-sm font-bold mb-2">Application:</label>
                                                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="application" placeholder="Facebook / Google / Yahoo / Zoom" v-model="form.application">
                                                    <!-- <div v-if="$page.errors.name" class="text-red-500">{{ $page.errors.name[0] }}</div> -->
                                                </div>
                                                <div class="mb-4">
                                                    <label for="link" class="block text-gray-700 text-sm font-bold mb-2">Link:</label>
                                                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="link" placeholder="https://www.example.com" v-model="form.link">
                                                    <!-- <div v-if="$page.errors.name" class="text-red-500">{{ $page.errors.name[0] }}</div> -->
                                                </div>
                                                <div class="mb-4">
                                                    <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
                                                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" placeholder="Enter Username/Email" v-model="form.username">
                                                    <!-- <div v-if="$page.errors.name" class="text-red-500">{{ $page.errors.username[0] }}</div> -->
                                                </div>
                                                <div class="mb-4">
                                                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password: (Auto generate if left blank)</label>
                                                    <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" placeholder="Enter Password" v-model="form.password">
                                                    <!-- <div v-if="$page.errors.name" class="text-red-500">{{ $page.errors.username[0] }}</div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" v-show="!editMode" @click="save(form)">
                                                Save
                                                </button>
                                            </span>
                                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" v-show="editMode" @click="update(form)">
                                                Update
                                                </button>
                                            </span>
                                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">

                                                <button @click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                                Cancel
                                                </button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'

    export default {
        components: {
            AppLayout,
        },
        props: {
            credentials: Array,
            errors: Object,
        },
        data() {
            return {
                editMode: false,
                isOpen: false,
                showPasswordSuccess: false,
                form: {
                    username: null,
                    password: null,
                    application: null,
                    link: null,
                },

            }
        },
         methods: {
            openModal: function () {
                this.isOpen = true;
            },
            closeModal: function () {
                this.isOpen = false;
                this.reset();
                this.editMode=false;
            },
            reset: function () {
                this.form = {
                    username: null,
                    password: null,
                    application: null,
                    link: null,
                }
            },
            save: function (data) {
                this.$inertia.post('/credentials', data)
                this.reset();
                this.closeModal();
                this.editMode = false;
            },
            edit: function (data) {
                this.form = Object.assign({}, data);
                this.editMode = true;
                this.openModal();
            },
            update: function (data) {
                data._method = 'PUT';
                this.$inertia.post('/credentials/' + data.id, data)
                this.reset();
                this.closeModal();
            },
            deleteRow: function (data) {
                if (!confirm('Are you sure want to remove?')) return;
                data._method = 'DELETE';
                this.$inertia.post('/posts/' + data.id, data)
                this.reset();
                this.closeModal();
            },

            getPassword: function (data) {
                var textArea = document.createElement("textarea");
               // textArea.hidden = true;
                textArea.innerHTML = data.password;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    var successful = document.execCommand('copy');
                    if(successful)
                    {
                        // SuccessCode

                    }
                    var msg = successful ? 'successful' : 'unsuccessful';
                    console.log('Copying text command was ' + msg);
                }
                catch (err)
                {
                    console.log('Oops, unable to copy');
                }

                document.body.removeChild(textArea);
            }
        }
    }
</script>
