let questionCount = 0;

function addQuestion() {
    questionCount++;

    const container = document.getElementById('questions-container');
    const surveyId = document.querySelector('input[name="survey_id"]').value;

    const questionDiv = document.createElement('div');
    questionDiv.innerHTML = `
    <div class="content">
        <label for="question_${questionCount}">本文:</label>
        <input type="text" name="questions[${questionCount}][title]" required>
        <input type="hidden" name="questions[${questionCount}][survey_id]" value="${surveyId}">
        <input type="hidden" name="questions[${questionCount}][question_num]" value="${questionCount}">
        
        <label for="type_${questionCount}">形式:</label>
        <select name="questions[${questionCount}][type]" required>
            <option value="0">自由記述</option>
            <option value="1">選択式</option>
            <option value="2">複数選択</option>
        </select>
        <button type="button" onclick="deleteQuestion(this)">削除</button>

        <div id="choices_${questionCount}" style="display:none;">
            <label for="choices_${questionCount}">選択肢:</label>
            <div id="options-container_${questionCount}">
            <!-- ここに選択肢が追加されます -->
            </div>
            <button type="button" onclick="addOptions(${questionCount})">選択肢を追加</button><br>
        </div>
    </div>
    `;

    container.appendChild(questionDiv);

    // 質問形式によって選択肢の表示・非表示を切り替える
    const typeSelect = questionDiv.querySelector(`select[name="questions[${questionCount}][type]"]`);
    const choicesDiv = questionDiv.querySelector(`div[id="choices_${questionCount}"]`);

    typeSelect.addEventListener('change', () => {
        choicesDiv.style.display = typeSelect.value === '1' || typeSelect.value === '2' ? 'block' : 'none';
    });
}
function deleteQuestion(button) {
    const question = button.parentNode;
    question.parentNode.removeChild(question);
    questionCount--;
  }

function addOptions(questionCount) {
    const choicesDiv = document.getElementById(`choices_${questionCount}`);
    const optionsContainer = document.getElementById(`options-container_${questionCount}`);
    
    if (choicesDiv && optionsContainer) {
        // 新しい選択肢追加時に新しい optionCount を生成
        const optionCount = optionsContainer.childElementCount + 1;

        const optionInput = document.createElement('div');
        optionInput.innerHTML = `
            <input type="text" name="options[${questionCount}][label][${optionCount}][text]" value="Option" required>
            <input type="hidden" name="options[${questionCount}][label][${optionCount}][option_id]" value="${optionCount}">
            <br>
            <button type="button" onclick="removeOption(this)">選択肢を削除</button>
        `;

        optionsContainer.appendChild(optionInput);
        choicesDiv.style.display = 'block'; // 選択肢が追加されたら表示する
    }
}

function removeOption(button) {
    const optionInput = button.parentNode;
    const optionsContainer = optionInput.parentNode;
    optionsContainer.removeChild(optionInput);

    // 選択肢がなくなったら非表示にする
    if (optionsContainer.childElementCount === 0) {
        const questionCount = optionsContainer.id.split('_')[2];
        const choicesDiv = document.getElementById(`choices_${questionCount}`);
        choicesDiv.style.display = 'none';
    }
}

function editQuestion() {
    var editContainer = document.getElementById('edit_container');
    var newQuestionDiv = document.createElement('div');
    newQuestionDiv.className = 'content';

    var newQuestionInput = document.createElement('input');
    newQuestionInput.type = 'text';
    newQuestionInput.name = 'new_questions[]'; // 新しい質問の名前を配列にすることで、PHP側で取り扱いやすくします

    var newQuestionTypeSelect = document.createElement('select');
    newQuestionTypeSelect.name = `questions[${editContainer.children.length}][type]`;
    newQuestionTypeSelect.required = true;

    var option1 = document.createElement('option');
    option1.value = '0';
    option1.text = '自由記述';

    var option2 = document.createElement('option');
    option2.value = '1';
    option2.text = '選択式';

    var option3 = document.createElement('option');
    option3.value = '2';
    option3.text = '複数選択';

    newQuestionTypeSelect.appendChild(option1);
    newQuestionTypeSelect.appendChild(option2);
    newQuestionTypeSelect.appendChild(option3);
    
    var addOptionsButton = document.createElement('button');
        addOptionsButton.innerText = '選択肢を追加';
        addOptionsButton.onclick = function() {
            editOptions(this.dataset.questionCount);
        };
        addOptionsButton.dataset.questionCount = editContainer.children.length;
        console.log('test');

    var deleteButton = document.createElement('button');
    deleteButton.innerText = '質問を削除';
    deleteButton.onclick = function() {
        newQuestionDiv.remove();
    };

    newQuestionDiv.appendChild(newQuestionInput);
    newQuestionDiv.appendChild(newQuestionTypeSelect);
    newQuestionDiv.appendChild(addOptionsButton);
    newQuestionDiv.appendChild(deleteButton);
    editContainer.appendChild(newQuestionDiv);
}

function handleTypeChange(selectElement) {
    // console.log('test')
    var questionCount = selectElement.dataset.questionCount;
    var selectedType = selectElement.value;

    // 選択されたタイプに応じて選択肢を追加する
    if (selectedType === '1' || selectedType === '2') {
        editOptions(questionCount);
    }
}

function editOptions(questionCount) {
    const choicesDiv = document.getElementById(`choices_${questionCount}`);
    const optionsContainer = document.getElementById(`options-container_${questionCount}`);
    
    if (choicesDiv && optionsContainer) {
        // 新しい選択肢追加時に新しい optionCount を生成
        const optionCount = optionsContainer.childElementCount + 1;

        const optionInput = document.createElement('div');
        optionInput.innerHTML = `
            <input type="text" name="options[${questionCount}][label][${optionCount}][text]" value="Option" required>
            <input type="hidden" name="options[${questionCount}][label][${optionCount}][option_id]" value="${optionCount}">
            <br>
            <button type="button" onclick="removeOption(this)">選択肢を削除</button>
        `;

        optionsContainer.appendChild(optionInput);
        choicesDiv.style.display = 'block'; // 選択肢が追加されたら表示する
    }
}

function addOptionsEdit(questionId) {
    const optionsContainer = document.getElementById(`options-container_${questionId}`);
    if (optionsContainer) {
        const optionCount = optionsContainer.children.length;
        const newOptionIndex = optionCount + 1;

        const optionInput = document.createElement('div');
        optionInput.innerHTML = `
            <input type="hidden" name="questions[${questionId}][options][${questionId}][question_id]" value="${questionId}">
            <input type="hidden" name="questions[${questionId}][options][${questionId}][number]" value="${newOptionIndex}">
            <input type="text" name="questions[${questionId}][options][${questionId}][text]" value="New Option" required>
            <br>
            <button class="delete" type="button" onclick="removeOption(this)">選択肢を削除</button>
        `;

        optionsContainer.appendChild(optionInput);
    }
}

function remove(index) {
    var editContainer = document.getElementById('edit_container');
    var questionToRemove = editContainer.children[index];
    questionToRemove.remove();
}

function deleteQuestion(questionId) {
    
    url = $('.delete').data('url');
    if (confirm('この設問を削除しますか？')) {
        // Ajaxリクエストを送信して設問を削除
        $.ajax({
            type: 'DELETE',
            url: url,
            data: {
                question_id: questionId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // 成功時の処理
                alert('設問が削除されました');
                // 画面を更新するなどの処理を行う
            },
            error: function(xhr, status, error) {
                // エラー時の処理
                console.error(xhr.responseText);
            }
        });
    }
}


