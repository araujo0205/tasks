<script setup lang="ts">
import { ref } from 'vue'

import { Head, InfiniteScroll, useHttp, router } from '@inertiajs/vue3';
import {
    Item,
    ItemActions,
    ItemContent,
    ItemDescription,
    ItemTitle,
} from '@/components/ui/item'
import ButtonGroup from '@/components/ui/button-group/ButtonGroup.vue';
import Button from '@/components/ui/button/Button.vue';
import ItemGroup from '@/components/ui/item/ItemGroup.vue';
import ItemSeparator from '@/components/ui/item/ItemSeparator.vue';
import { toast } from 'vue-sonner'
import TaskEdit from '@/components/tasks/TaskEdit.vue';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog'
import { destroy, update } from '@/actions/App/Http/Controllers/TaskController';
import {
    Empty,
    EmptyContent,
    EmptyDescription,
    EmptyHeader,
    EmptyMedia,
    EmptyTitle,
} from '@/components/ui/empty'
import { Check, Inbox, Pen, Trash } from 'lucide-vue-next';


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
    tasks: {
        data: App.Data.TaskData[];
    };
}>();



function taskDone(task: App.Data.TaskData) {
    task.status = 'done'
    router.patch(update(task.id), task)
}

function taskUpdate(task: App.Data.TaskData, newValues: App.Data.TaskData) {
    task.title = newValues.title
    task.description = newValues.description

    router.patch(update(task.id), task, {
        onSuccess: () => {
            editDrawerProps.value.visible = false
        }
    })
}

const editDrawerProps = ref({
    visible: false,
    task: null,
})

function openEditDrawer(task: App.Data.TaskData) {
    editDrawerProps.value.visible = true
    editDrawerProps.value.task = task
}

const deleteDialog = ref({
    visible: false,
    task: null
})

function openConfirmDelete(task: App.Data.TaskData) {
    deleteDialog.value.visible = true
    deleteDialog.value.task = task
}

function deleteTask(task: App.Data.TaskData) {
    router.delete(destroy(task.id))
}
</script>

<template>

    <Head title="Tarefas" />
    <div class="flex gap-3 rounded-md border p-4 flex-col">
        <ItemGroup>
            <InfiniteScroll data="tasks">
                <TransitionGroup name="tasks" tag="div">
                    <Empty v-if="props.tasks.data.length == 0">
                        <EmptyHeader>
                            <EmptyMedia variant="icon">
                                <Inbox />
                            </EmptyMedia>
                            <EmptyTitle>
                                Não há tarefas
                            </EmptyTitle>
                        </EmptyHeader>
                    </Empty>
                    <div v-for="(task, index) in props.tasks.data" :key="task.id" class="w-full">
                        <Item>
                            <ItemContent class="gap-1">
                                <ItemTitle>{{ task.title }}</ItemTitle>
                                <ItemDescription>{{ task.description }}</ItemDescription>
                            </ItemContent>
                            <ItemActions>
                                <ButtonGroup>
                                    <Button size="sm" variant="outline" @click="taskDone(task)">
                                        <Check /> Concluir
                                    </Button>
                                    <Button size="sm" variant="outline" @click="openEditDrawer(task)">
                                        <Pen /> Editar
                                    </Button>
                                    <Button size="sm" variant="outline" @click="openConfirmDelete(task)">
                                        <Trash /> Excluir
                                    </Button>
                                </ButtonGroup>
                            </ItemActions>
                        </Item>
                        <ItemSeparator v-if="index !== props.tasks.data.length - 1" />
                    </div>
                </TransitionGroup>
            </InfiniteScroll>
            <TaskEdit :task="editDrawerProps.task" v-model:open="editDrawerProps.visible"
                @submit="(task, newValues) => taskUpdate(task, newValues)" />
            <AlertDialog v-model:open="deleteDialog.visible">
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Deletar Tarefa</AlertDialogTitle>
                        <AlertDialogDescription>
                            Deseja realmente deletar essa tarefa ?
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel>Não</AlertDialogCancel>
                        <AlertDialogAction @click="deleteTask(deleteDialog.task)">Sim</AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </ItemGroup>
    </div>
</template>

<style scoped>
.tasks-move,
.tasks-enter-active,
.tasks-leave-active {
    transition: all 0.4s ease;
}

.tasks-enter-from,
.tasks-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

.tasks-leave-active {
    position: absolute;
}
</style>
