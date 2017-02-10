<?php

use Illuminate\Database\Seeder;

class PeopleKRATableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('feedback_metrics')->delete();

        $data = [
            ['metrics' => "Project Role(s) and Responsibilities",
                'metrics_rating' => '0',
                'category_id' => '1',
                'sort' => '1',
            ],
            ['metrics' => "Suggestions for Development",
                'metrics_rating' => '0',
                'category_id' => '1',
                'sort' => '2',
            ],
            ['metrics' => "Client interaction",
                'metrics_rating' => '1',
                'category_id' => '1',
                'sort' => '3',
            ],
            ['metrics' => "Timely project delivery",
                'metrics_rating' => '1',
                'category_id' => '1',
                'sort' => '4',
            ],
            ['metrics' => "Quality project delivery",
                'metrics_rating' => '1',
                'category_id' => '1',
                'sort' => '5',
            ],
            ['metrics' => "Effort Estimation of Projects",
                'metrics_rating' => '1',
                'category_id' => '1',
                'sort' => '6',
            ],
            ['metrics' => "Release management",
                'metrics_rating' => '1',
                'category_id' => '1',
                'sort' => '7',
            ],
            ['metrics' => "Reporting to management",
                'metrics_rating' => '1',
                'category_id' => '1',
                'sort' => '8',
            ],
            ['metrics' => "Documentation & Archival of Projects",
                'metrics_rating' => '1',
                'category_id' => '1',
                'sort' => '9',
            ],
            ['metrics' => "Process Adherence",
                'metrics_rating' => '1',
                'category_id' => '1',
                'sort' => '10',
            ],
            ['metrics' => "Status reporting",
                'metrics_rating' => '1',
                'category_id' => '1',
                'sort' => '11',
            ],
            ['metrics' => "Problem solving",
                'metrics_rating' => '1',
                'category_id' => '1',
                'sort' => '12',
            ],
            ['metrics' => "Exploring newer tools and solutions",
                'metrics_rating' => '1',
                'category_id' => '1',
                'sort' => '13',
            ],
            ['metrics' => "Monitor and manage project",
                'metrics_rating' => '1',
                'category_id' => '1',
                'sort' => '14',
            ],
            ['metrics' => "Resource back up plan",
                'metrics_rating' => '1',
                'category_id' => '1',
                'sort' => '15',
            ],
            ['metrics' => "Creation of SOW & RFP/RFI",
                'metrics_rating' => '1',
                'category_id' => '1',
                'sort' => '16',
            ],
            ['metrics' => "Establish client communication",
                'metrics_rating' => '1',
                'category_id' => '1',
                'sort' => '17',
            ],
            ['metrics' => "Build/Improve Competency",
                'metrics_rating' => '1',
                'category_id' => '2',
                'sort' => '1',
            ],
            ['metrics' => "Company branding",
                'metrics_rating' => '1',
                'category_id' => '2',
                'sort' => '2',
            ],
            ['metrics' => "Build newer solutions",
                'metrics_rating' => '1',
                'category_id' => '2',
                'sort' => '3',
            ],
            ['metrics' => "Lead creation",
                'metrics_rating' => '1',
                'category_id' => '2',
                'sort' => '4',
            ],
            ['metrics' => "Participation in public events",
                'metrics_rating' => '1',
                'category_id' => '2',
                'sort' => '5',
            ],
            ['metrics' => "Help creation of marketing material",
                'metrics_rating' => '1',
                'category_id' => '2',
                'sort' => '6',
            ],
            ['metrics' => "Identify future opportunities",
                'metrics_rating' => '1',
                'category_id' => '2',
                'sort' => '7',
            ],
            ['metrics' => "Continuous Self Improvement",
                'metrics_rating' => '1',
                'category_id' => '3',
                'sort' => '1',
            ],
            ['metrics' => "Communication and presentation skills",
                'metrics_rating' => '1',
                'category_id' => '3',
                'sort' => '2',
            ],
            ['metrics' => "Being a Team Player",
                'metrics_rating' => '1',
                'category_id' => '3',
                'sort' => '3',
            ],
            ['metrics' => "Interaction with Team",
                'metrics_rating' => '1',
                'category_id' => '3',
                'sort' => '4',
            ],
            ['metrics' => "Participation in Talent Acquisition",
                'metrics_rating' => '1',
                'category_id' => '3',
                'sort' => '5',
            ],
            ['metrics' => "Conflict Resolution",
                'metrics_rating' => '1',
                'category_id' => '3',
                'sort' => '6',
            ],
            ['metrics' => "Team building activity",
                'metrics_rating' => '1',
                'category_id' => '3',
                'sort' => '7',
            ],
            ['metrics' => "Mentoring",
                'metrics_rating' => '1',
                'category_id' => '3',
                'sort' => '8',
            ],
            ['metrics' => "Training activity",
                'metrics_rating' => '1',
                'category_id' => '3',
                'sort' => '9',
            ],
            ['metrics' => "Maintaining relationship with Peers/Subordinates and Superiors",
                'metrics_rating' => '1',
                'category_id' => '3',
                'sort' => '10',
            ],
            ['metrics' => "Participation in Org Activities",
                'metrics_rating' => '1',
                'category_id' => '3',
                'sort' => '11',
            ],
        ];
        foreach ($data as $key => $dataValue) {
            DB::table('feedback_metrics')->insert([
                'metrics' => $dataValue['metrics'],
                'metrics_rating' => $dataValue['metrics_rating'],
                'category_id' => $dataValue['category_id'],
                'sort' => $dataValue['sort'],
                'status' => '1',
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s"),
            ]);
        }
    }
}