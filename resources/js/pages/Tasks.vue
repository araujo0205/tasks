<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Tarefas',
                href: '#',
            },
        ],
    },
});

const props = defineProps<{
    tasks: Array<{ id: number; title: string }>;
}>();
</script>

<template>
    <Head title="Tarefas" />

    <div class="flex items-center gap-3 rounded-md border p-4 flex-col">
        <div v-for="task in props.tasks" :key="task.id" class="flex items-center gap-2">
             <Checkbox :id="'task-' + task.id" />
             <Label :for="'task-' + task.id">{{ task.title }}</Label>
        </div>
    </div>
</template> 