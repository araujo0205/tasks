<script setup lang="ts">
import { watch } from 'vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
    Sheet,
    SheetContent,
    SheetFooter,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet'
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { Field as VeeField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import {
    Field,
    FieldError,
    FieldLabel
} from '@/components/ui/field'

import * as z from 'zod'

const props = defineProps<{
    task: App.Data.TaskData | null;
}>();

const open = defineModel<boolean>('open')

const emits = defineEmits(['submit'])

const formSchema = z.object({
    title: z.string().min(5, "Mínimo 5 caracteres"),
    description: z.string().nullable()
})

const { handleSubmit, resetForm } = useForm({
    validationSchema: toTypedSchema(formSchema),
    initialValues: {
        title: props.task?.title,
        description: props.task?.description
    },
})

const onSubmit = handleSubmit((values) => {
    emits('submit', props.task, values)
})


watch(() => props.task, (newValue) => {
    resetForm({
        values: {
            title: newValue?.title,
            description: newValue?.description
        }
    })
})
</script>

<template>
    <Sheet v-model:open="open">
        <SheetTrigger as-child>
            <slot />
        </SheetTrigger>
        <SheetContent @open-auto-focus="(e) => e.preventDefault()">
            <SheetHeader>
                <SheetTitle>Editar Tarefa</SheetTitle>
            </SheetHeader>
            <form id="form-vee" @submit="onSubmit" class="grid flex-1 auto-rows-min gap-6 px-4">
                <VeeField v-slot="{ componentField, errors }" name="title" class="grid gap-3">
                    <Field :data-invalid="!!errors.length">
                        <FieldLabel for="form-vee-title">Título</FieldLabel>
                        <Input id="form-vee-title" v-bind="componentField" />
                    </Field>
                    <FieldError v-if="errors.length" :errors="errors" />
                </VeeField>
                <VeeField v-slot="{ componentField, errors }" name="description" class="grid gap-3">
                    <Label for="form-vee-description">Descrição</Label>
                    <Textarea id="form-vee-description" v-bind="componentField" />
                    <FieldError v-if="errors.length" :errors="errors" />
                </VeeField>
            </form>
            <SheetFooter>
                <Button type="submit" form="form-vee">
                    Salvar
                </Button>
            </SheetFooter>
        </SheetContent>
    </Sheet>
</template>
