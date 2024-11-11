<script setup>
import { onMounted, ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

const loading = ref(false);
const showModal = ref(false);
const showUpdate = ref(false);
const toDoTasks = ref([]);
const formData = ref({
    file: null,
    title: "",
    description: "",
    status: null,
});

const currentTask = ref({
    title: "",
    description: "",
    file: null,
});
const openUpdateModal = (task) => {
    currentTask.value = { ...task };
    showUpdate.value = true;
};

const closeUpdateModal = () => {
    showUpdate.value = false;
    currentTask.value = null;
};
const openEditModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const handleFileChange = (event) => {
    const selectedFile = event.target.files[0];
    if (selectedFile) {
        formData.value.file = selectedFile;
    }
};

const fetchTasks = async () => {
    try {
        const response = await axios.get("/tasks", {
            params: {
                status: "To Do",
            },
        });
        toDoTasks.value = response.data;
    } catch (error) {}
};
const fetchInprogress = async () => {
    try {
        const response = await axios.get("/tasks", {
            params: {
                status: "In Progress",
            },
        });
        inProgress.value = response.data;
    } catch (error) {}
};

const fetchDone = async () => {
    try {
        const response = await axios.get("/tasks", {
            params: {
                status: "Done",
            },
        });
        done.value = response.data;
    } catch (error) {}
};

const fetchCancelled = async () => {
    try {
        const response = await axios.get("/tasks", {
            params: {
                status: "Cancelled",
            },
        });
        cancelled.value = response.data;
    } catch (error) {}
};

const addTask = async () => {
    // if (loading.value) return;
    // loading.value = true;
    try {
        const payload = new FormData();
        payload.append("title", formData.value.title);
        payload.append("description", formData.value.description);

        if (formData.value.file) {
            payload.append("file", formData.value.file);
        }
        const response = await axios.post("/add-task", payload, {
            headers: {},
        });

        formData.value.title = "";
        formData.value.description = "";
        formData.value.file = null;
        console.log("Task Added Successfully");
        showModal.value = false;
        fetchTasks();
    } catch (error) {
        console.log("Failed to Add Task");
    }
};

const deleteTask = async () => {
    try {
        await axios.delete(`/delete-task/${currentTask.value.id}`);

        fetchTasks();
        closeUpdateModal();
    } catch (error) {}
};
onMounted(() => {
    fetchTasks();
    fetchInprogress();
    fetchDone();
    fetchCancelled();
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12 px-4 flex flex-wrap justify-center space-x-6">
            <div class="bg-white rounded-md shadow-md w-52">
                <div class="bg-green-100 p-2 rounded-t-md text-green-800">
                    <span class="font-semibold"
                        ><font-awesome-icon
                            icon="fa-solid fa-clipboard"
                            class="mr-2"
                        />To Do</span
                    >
                </div>

                <div v-for="task in toDoTasks" :key="task.id" class="py-1 px-2">
                    <div
                        @click="openUpdateModal(task)"
                        class="p-2 bg-green-50 rounded-sm shadow-sm border-gray-50"
                    >
                        <h2>{{ task.title }}</h2>
                    </div>
                </div>

                <div class="space-y-2">
                    <div></div>
                </div>

                <div class="space-y-3 p-2">
                    <div>
                        <button
                            @click="openEditModal()"
                            class="bg-gray-100 rounded-md text-xs py-2 px-2 w-full text-gray-500 hover:bg-gray-300"
                        >
                            <font-awesome-icon
                                icon="fa-solid fa-plus"
                                class="mr-2"
                            />
                            Add task
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-md shadow-md w-52">
                <div class="bg-yellow-100 p-2 rounded-t-md text-yellow-800">
                    <span class="font-semibold"
                        ><font-awesome-icon
                            icon="fa-solid fa-list-check"
                            class="mr-2"
                        />In Progress</span
                    >
                </div>
            </div>
            <div class="bg-white rounded-md shadow-md w-52">
                <div class="bg-blue-100 p-2 rounded-t-md text-blue-800">
                    <span class="font-semibold"
                        ><font-awesome-icon
                            icon="fa-solid fa-check"
                            class="mr-2"
                        />Completed</span
                    >
                </div>
            </div>

            <div class="bg-white rounded-md shadow-md w-52">
                <div class="bg-red-100 p-2 rounded-t-md text-red-800">
                    <span class="font-semibold"
                        ><font-awesome-icon
                            icon="fa-solid fa-ban"
                            class="mr-2"
                        />Cancelled</span
                    >
                </div>
            </div>
        </div>

        <div
            v-if="showModal"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div class="bg-white p-4 rounded shadow-md w-1/2">
                <div class="flex flex-row justify-between">
                    <h3 class="text-lg font-semibold">Add Task</h3>
                    <button @click="closeModal">
                        <font-awesome-icon
                            icon="fa-solid fa-circle-xmark"
                            class="mr-2"
                        />
                    </button>
                </div>
                <InputLabel for="title" value="Title" />

                <TextInput
                    id="title"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="formData.title"
                    required
                    autofocus
                />
                <InputLabel for="description" value="Description" />
                <textarea
                    name="description"
                    id="description"
                    v-model="formData.description"
                    class="w-full border-gray-200 rounded-md shadow-sm"
                ></textarea>
                <InputLabel for="file" value="Attachment" />
                <input
                    type="file"
                    @change="handleFileChange"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    required
                />

                <div class="flex flex-row mt-2 space-x-2">
                    <DangerButton @click="closeModal" :disabled="loading">
                        Discard
                    </DangerButton>
                    <PrimaryButton @click="addTask" :disabled="loading"
                        ><span v-if="loading" class="loader"></span>
                        Confirm</PrimaryButton
                    >
                </div>
            </div>
        </div>

        <div
            v-if="showUpdate"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div class="bg-white p-4 rounded shadow-md w-1/2">
                <div class="flex flex-row justify-between mb-4">
                    <h3 class="text-lg font-semibold">Update Task</h3>
                    <button @click="closeUpdateModal">
                        <font-awesome-icon
                            icon="fa-solid fa-circle-xmark"
                            class="mr-2"
                        />
                    </button>
                </div>

                <div class="flex flex-row space-x-2">
                    <div class="w-1/2">
                        <InputLabel for="title" value="Title" />
                        <TextInput
                            id="task"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="currentTask.title"
                            required
                            autofocus
                        />

                        <InputLabel for="description" value="Description" />
                        <textarea
                            name="description"
                            id="description"
                            v-model="currentTask.description"
                            class="w-full border-gray-200 rounded-md shadow-sm"
                        ></textarea>

                        <!-- <span>{{ currentTask.creator.name }}</span> -->
                        <h3>Assign Users</h3>
                    </div>
                    <div>
                        <iframe
                            :src="`/storage/${currentTask.file}`"
                            frameborder="0" class="h-full"
                        ></iframe>
                    </div>
                </div>

                <div class="flex flex-row mt-2 space-x-2">
                    <DangerButton @click="deleteTask"> Delete </DangerButton>
                    <PrimaryButton @click="closeUpdateModal">
                        Update Task</PrimaryButton
                    >
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.loader {
    display: inline-block;
    border: 2px solid transparent;
    border-top: 2px solid #3498db;

    border-radius: 50%;
    width: 16px;
    height: 16px;
    animation: spin 1s linear infinite;
    margin-right: 8px;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>
