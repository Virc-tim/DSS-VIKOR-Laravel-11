import './bootstrap';

function updateInput(input, index) {
    const prefix = `C${index} - `;
    const currentValue = input.value;

    if (!currentValue.startsWith(prefix)) {
        input.value = `${prefix}${currentValue.replace(prefix, '')}`;
    }
}
