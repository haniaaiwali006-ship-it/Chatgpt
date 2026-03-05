<?php

class ChatBot {
    
    public function getResponse($message) {
        $message = strtolower(trim($message));

        if (strpos($message, 'who is rehan allha wala') !== false || strpos($message, 'rehan allah wala') !== false) {
            return "Rehan Allah Wala is a prominent Pakistani motivational speaker, entrepreneur, and social activist. He is known for his mission to eradicate poverty through digital education and entrepreneurship. He advocates for using mobile phones and the internet to learn new skills and earn specific incomes. He has initiated various projects to empower the youth of Pakistan.";
        }

        if (strpos($message, 'what is ai') !== false) {
            return "Artificial Intelligence (AI) refers to the simulation of human intelligence in machines that are programmed to think like humans and mimic their actions. The term may also be applied to any machine that exhibits traits associated with a human mind such as learning and problem-solving.";
        }

        if (strpos($message, 'what is css') !== false) {
            return "CSS (Cascading Style Sheets) is a stylesheet language used for describing the presentation of a document written in a markup language like HTML. CSS is a cornerstone technology of the World Wide Web, alongside HTML and JavaScript. It allows you to control the color, font, the text size, the spacing between elements, how elements are positioned and laid out, what background images or background colors are to be used, different displays for different devices, and screen sizes, and much more.";
        }

        if (strpos($message, 'hania amir') !== false) {
            return "Hania Aamir is a popular Pakistani film and television actress. She began her career with the comedy film Janaan (2016) and has since appeared in several successful television dramas and films. She is known for her vibrant personality and acting skills.";
        }
        
        if (strpos($message, 'famous peoples') !== false || strpos($message, 'famous people') !== false) {
            return "Here are some famous people from around the world:\n1. **Elon Musk** - CEO of Tesla and SpaceX.\n2. **Bill Gates** - Co-founder of Microsoft.\n3. **Jeff Bezos** - Founder of Amazon.\n4. **Barack Obama** - Former US President.\n5. **Cristiano Ronaldo** - Football legend.\n6. **Malala Yousafzai** - Nobel Prize laureate.";
        }

        if (strpos($message, 'famous places') !== false) {
            return "Here are some famous places in the world:\n1. **Eiffel Tower** (Paris, France)\n2. **Great Wall of China** (China)\n3. **Taj Mahal** (Agra, India)\n4. **Statue of Liberty** (New York, USA)\n5. **Machu Picchu** (Peru)\n6. **Pyramids of Giza** (Egypt)";
        }

        if (strpos($message, 'coding') !== false) {
            return "Coding, or computer programming, is the process of designing and building an executable computer program to accomplish a specific computing result or to perform a specific task. Programming involves tasks such as analysis, generating algorithms, profiling algorithms' accuracy and resource consumption, and the implementation of algorithms in a chosen programming language (commonly referred to as coding).";
        }

        if (strpos($message, 'create a table for daily routine') !== false || strpos($message, 'daily routine') !== false) {
            return "| Time | Activity |\n|---|---|\n| 6:00 AM | Wake up & Morning Prayer |\n| 6:30 AM | Exercise / Jogging |\n| 7:30 AM | Breakfast |\n| 8:30 AM | Work / Study Session 1 |\n| 12:30 PM | Lunch Break |\n| 1:30 PM | Work / Study Session 2 |\n| 5:00 PM | Relax / Hobby |\n| 7:00 PM | Dinner |\n| 8:00 PM | Reading / Planning for tomorrow |\n| 10:00 PM | Sleep |";
        }

        // Default generic responses simulation
        return $this->getGenericResponse();
    }

    private function getGenericResponse() {
        $responses = [
            "That's an interesting topic! Can you tell me more?",
            "I'm here to help. What else would you like to know?",
            "I can answer questions about AI, CSS, famous people, and more. Try asking me 'What is CSS?' or 'Who is Rehan Allah Wala?'",
            "I am a simulated AI chatbot. How can I assist you today?",
            "Could you clarify your question? I'm programmed to answer specific queries."
        ];
        return $responses[array_rand($responses)];
    }
}
?>
