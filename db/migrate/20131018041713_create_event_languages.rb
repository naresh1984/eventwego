class CreateEventLanguages < ActiveRecord::Migration
  def change
    create_table :event_languages do |t|
      t.string :name

      t.timestamps
    end
  end
end
