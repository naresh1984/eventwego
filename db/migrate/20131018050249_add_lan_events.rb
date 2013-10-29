class AddLanEvents < ActiveRecord::Migration
  def up
  change_column :events, :event_language_id, :string
  end

  def down
  end
end
