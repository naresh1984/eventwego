class AddEventTypeEvents < ActiveRecord::Migration
  def up
 add_column :events, :event_type_id, :integer
 add_column :events, :event_language_id, :integer
  end

  def down
  end
end
