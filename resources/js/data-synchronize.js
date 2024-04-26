$(() => {
    const exportFile = ($form, data, button) => {
        const httpClient = $httpClient.make().withResponseType('blob')

        if (button) {
            httpClient.withButtonLoading(button)
        }

        httpClient
            .post($form.prop('action'), data)
            .then(({ headers, data }) => {
                const [_, filename] = headers['content-disposition'].split('filename=')
                const url = window.URL.createObjectURL(data)
                const a = document.createElement('a')
                a.href = url
                a.download = filename
                a.click()
                window.URL.revokeObjectURL(url)

                Botble.showSuccess($form.data('success-message'))
            })
            .catch(() => {
                Botble.showError($form.data('error-message'))
            })
    }

    const $form = $(document).find('[data-bb-toggle="import-form"]')

    if ($form.length > 0) {
        const formData = new FormData($form.get(0))
        const $button = $form.find('button[type="submit"]')
        const $errors = $form.find('[data-bb-toggle="import-errors"]')
        const $output = $form.find('.data-synchronize-import-output')
        let errors = []
        let total = null

        $form.on('change', (e) => {
            formData.set(e.target.name, e.target.value)
        })

        const output = (message, type) => {
            if (type) {
                $output.append(`<p class="text-${type}">${message}</p>`)
            } else {
                $output.append(`<p>${message}</p>`)
            }

            $output.scrollTop($output[0].scrollHeight)
        }

        const dropzone = new Dropzone($form.find('.dropzone').get(0), {
            url: $form.prop('action'),
            headers: {
                'X-CSRF-TOKEN': $form.find('input[name="_token"]').val(),
            },
            previewTemplate: $('#data-synchronize-import-preview-template').html(),
            acceptedFiles: $form.data('accepted-files'),
            maxFiles: 1,
            autoProcessQueue: false,
            chunking: true,
            chunkSize: 1024 * 1024,
            maxfilesexceeded: function (file) {
                this.removeAllFiles()
                this.addFile(file)
            },
        })

        const cleanup = () => {
            Botble.hideButtonLoading($button)
            $button.prop('disabled', true).addClass('disabled')
            dropzone.removeAllFiles()
        }

        const importData = (fileName, offset, limit = $form.data('chunk-size'), total = 0) => {
            formData.set('file_name', fileName)
            formData.set('offset', offset)
            formData.set('limit', limit)
            formData.set('total', total)

            $httpClient
                .make()
                .post($form.data('import-url'), formData)
                .then(({ data }) => {
                    if (data.data.count > 0) {
                        output(data.message)
                        importData(fileName, data.data.offset + limit, limit, data.data.total)
                    } else {
                        output(data.message, 'success')
                        cleanup()
                    }
                })
                .catch(() => cleanup())
        }

        const validate = (fileName, offset, limit = $form.data('chunk-size')) => {
            formData.set('file_name', fileName)
            formData.set('offset', offset)
            formData.set('limit', limit)

            $httpClient
                .make()
                .post($form.data('validate-url'), formData)
                .then(({ data }) => {
                    if (data.data.errors.length > 0) {
                        errors = errors.concat(data.data.errors)
                    }

                    if (total === null) {
                        total = data.data.total
                    }

                    if (data.message) {
                        output(data.message)
                    }

                    if (data.data.count > 0) {
                        validate(data.data.file_name, data.data.offset + limit, limit)
                    } else {
                        if (errors.length === 0) {
                            importData(data.data.file_name, 0)
                        } else {
                            output($form.data('validate-failed-message'), 'danger')
                        }
                    }

                    if (errors.length > 0) {
                        $errors.find('ul').html(errors.map((error) => `<li>${error}</li>`).join(''))
                        $errors.show()

                        cleanup()
                    }
                })
                .catch(() => cleanup())
        }

        dropzone.on('sending', () => {
            $output.empty()
            $output.show()

            output($form.data('uploading-message'))
            Botble.showButtonLoading($button)
        })

        dropzone.on('success', (file, { data, error, message }) => {
            if (error) {
                output(message, 'danger')
                cleanup()

                return
            }

            output(message)
            validate(data.file_name, 0)
        })

        dropzone.on('addedfile', () => {
            $button.prop('disabled', false).removeClass('disabled')
        })

        $form.on('submit', (e) => {
            e.preventDefault()

            if (dropzone.getQueuedFiles().length > 0) {
                dropzone.processQueue()
            }
        })
    }

    $(document)
        .on('click', '[data-bb-toggle="export-data"] button', (e) => {
            e.preventDefault()

            const $button = $(e.currentTarget)
            const $form = $button.closest('form')

            exportFile($form, $form.serialize(), $button)
        })
        .on('click', '[data-bb-toggle="quick-export"]', (e) => {
            e.preventDefault()

            exportFile($('[data-bb-toggle="export-data"]'), {
                format: $(e.currentTarget).data('value'),
            })
        })
})
