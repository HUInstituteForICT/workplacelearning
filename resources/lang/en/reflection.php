<?php

return [
    'reflection'          => 'Reflection',
    'add-new-reflection'  => 'Add reflection',
    'none-attached'       => 'No reflection added',
    'remove'              => 'Remove',
    'delete-confirmation' => 'Are you sure you want to delete this reflection? Any unsaved data will be lost.',
    'short_reflection' => 'Short reflection',
    'full_reflection' => 'Detailed reflection',

    'fields' => [
        'starr'     => [
            'situation'  => 'Situation',
            'task'       => 'Task',
            'action'     => 'Action',
            'result'     => 'Result',
            'reflection' => 'Reflection',
        ],
        'korthagen' => [
            'fase1' => 'Phase 1',
            'fase2' => 'Phase 2',
            'fase3' => 'Phase 3',
            'fase4' => 'Phase 4',
            'fase5' => 'Phase 5',
        ],
        'abcd'      => [
            'cause'      => 'A: Cause',
            'importance' => 'B: Importance',
            'conclusion' => 'C: Conclusion',
            'todo'       => 'D: To do',
        ],
        'pdca'      => [
            'plan'  => 'Plan',
            'do'    => 'Do',
            'check' => 'Check',
            'act'   => 'Act',
        ],
    ],


    'types' => [
        'starr'     => [
            'situation'  => "What was the situation?\n\n- What happened?\n\n\n- Who were involved?\n\n\n- Where did it happen?\n\n\n- When did it happen?\n\n",
            'task'       => "General\n- What was your task?\n\n\n- What was your role?\n\n\n- What was expected of you?\n\n\nPersonal\n- What did you want to achieve?\n\n\n- Wat did you expect of yourself in that situation?\n\n\n- What did you think you had to do?\n\n",
            'action'     => "- What did you say or do?\n\n\n- What was your approach?\n\n\n- And then?\n\n\n- How did other respond to that?\n\n\n- What did you then say or do?\n\n\n- And then?\n\n",
            'result'     => "- What came of it?\n\n\n- How did it end?\n\n\n- What was the result of your actions?\n\n\n- How did others react?\n\n",
            'reflection' => "- How do you think you did?\n\n\n- Were you satisfied with the result?\n\n\n- What would you do differently next time?\n\n\n- What do you need for that?\n\n",
        ],
        'korthagen' => [
            'fase1' => "Detail the situation you are reflecting on?\n- What was the situation?\n\n\n- What was my task in this situation?\n\n\n- Which actions did I take in this situation?\n\n\n- What were the results of these actions?\n\n",
            'fase2' => "What really happened?\n\n- What did I see?\n\n\n- What did I do?\n\n\n- What did I think?\n\n\n- What did I feel?\n\n",
            'fase3' => "Awareness of essential aspects\n\n- What does this mean for me?\n\n\n- What is the issue or positive discovery?\n\n\n- What caused this? What does it have to do with?\n\n",
            'fase4' => "Alternatives\n\n- What alternatives do I see (solutions or ways to make use of my discovery)?\n\n\n- What are the (dis)advantages?\n\n\n- What do you intend to do for the next time?\n\n",
            'fase5' => "Trying out\n\n- What do I want to achieve?\n\n\n- What do I want to pay attention to?\n\n\n- What do I want to try?\n\n",


            'fase1' => "Act/Gaining experience\n\nAnswer one of the following questions?\n\n1 What did I want to achieve?\n\n\n1 What did I want to pay attention to?\n\n\n1 What did I want to try?\n\n",
            'fase2' => "Looking back\n\nME\n2 What did I do?\n\n\n3 What did I think?\n\n\n4 What did I feel?\n\nTHE STUDENTS\n5 What did you think the students thought?\n\n\n6 How did you think the students felt?\n\n\n7 What did the students do?\n\n",
            'fase3' => "Formulating essential aspects\n\n8 How are the previous questions related?\n\n\n9 What is the influence of the context / school in total?\n\n\n10 What does that now mean for me?\n(Use 8 and 9 in your answer)\n\nAnd/or\n\n11 What is the problem (or positive discovery)?\n(Based on your answers of 8, 9 and 10)\n\n",
            'fase4' => "Alternatives\n\n12 What alternatives do I see (what solutions for the issue or ways to use my discovery)?\n\n\n13 What (dis)advantages have they?\n(Answer per alternative!)\n\n\n14 What do I intend to do next time?\n(Be as explicit as possible!)",
        ],
        'abcd'      => [
            'cause'      => "What happened? What was the cause?\n\n",
            'importance' => "What was important of that for me?\n\n",
            'conclusion' => "What insight does this lead to?\n\n",
            'todo'       => "What do you intend to do?\n\n",
        ],
        'pdca' => [
            'plan'  => "",
            'do'    => "",
            'check' => "",
            'act' => ""
        ]
    ],
];