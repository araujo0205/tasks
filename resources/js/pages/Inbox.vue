<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { inbox } from '@/routes';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { ref } from 'vue';
import { Form } from "@inertiajs/vue3";
import { store } from "@/actions/App/Http/Controllers/TaskController";
const task = ref('');

const handleSuccess = () => {
    task.value = '';
};

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Adicionar Tarefa',
                href: inbox(),
            },
        ],
    },
});

</script>

<template>
    <Head title="Adicionar Tarefa" />
        <Form method="post" :action="store()" @success="handleSuccess" class="flex items-center justify-center h-screen flex-col gap-4">
            <Textarea name="title" placeholder="Digite sua tarefa aqui..." v-model="task" class="w-full max-w-md min-h-[200px]" />
            <Button class="ml-4" type="submit" :disabled="!task">Adicionar</Button>
        </Form>
</template>

